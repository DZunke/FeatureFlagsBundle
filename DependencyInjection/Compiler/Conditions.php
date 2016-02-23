<?php

namespace DZunke\FeatureFlagsBundle\DependencyInjection\Compiler;

use DZunke\FeatureFlagsBundle\Toggle\Flag;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class Conditions implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig('d_zunke_feature_flags');
        $config = reset($configs);

        $this->configureToggle($config, $container);
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     */
    private function configureToggle(array $config, ContainerBuilder $container)
    {
        $config = $this->normalizeConfig($config);

        $definition = $container->getDefinition('dz.feature_flags.toggle');
        $definition->addMethodCall('setDefaultState', [$config['default']]);

        $this->fillConditionsBag($container);

        foreach ($config['flags'] as $name => $flagConfig) {
            $flagConfig = $this->normalizeFlagConfig($flagConfig, $config['default']);

            $flagDefinition = $this->createFlagDefinition(
                $name,
                new Reference('dz.feature_flags.conditions_bag'),
                $flagConfig['default']
            );

            $this->processFlagConditions($flagDefinition, $flagConfig['conditions_config']);

            $container->setDefinition('dz.feature_flags.toggle.flag.' . $name, $flagDefinition);
            $definition->addMethodCall('addFlag', [$flagDefinition]);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function fillConditionsBag(ContainerBuilder $container)
    {
        $taggedConditions = $container->findTaggedServiceIds('dz.feature_flags.toggle.condition');

        if (empty($taggedConditions)) {
            return;
        }

        $containerBag = $container->getDefinition('dz.feature_flags.conditions_bag');

        foreach ($taggedConditions as $service => $options) {
            $options = reset($options);
            $containerBag->addMethodCall('set', [$options['alias'], new Reference($service)]);

        }
    }

    /**
     * Normalize config object so it can always be processed even
     * if there are values missing from the config object
     *
     * @param array|null $config
     *
     * @return array
     */
    private function normalizeConfig(array $config = null)
    {
        if (false === is_array($config)) {
            $config = [];
        }

        if (true === array_key_exists('flags', $config) && false === is_array($config['flags'])) {
            unset($config['flags']);
        }

        return array_merge([
            'default' => true,
            'flags' => [],
        ], $config);
    }

    /**
     * Normalizes flag config to prevent the code from breaking
     * whenever a property or maybe multiple properties do not exist
     *
     * @param array|null $config
     * @param bool $defaultActive
     *
     * @return array
     */
    private function normalizeFlagConfig(array $config = null, $defaultActive)
    {
        if (false === is_array($config)) {
            $config = [];
        }

        if (true === array_key_exists('conditions_config', $config) && false === is_array($config['conditions_config'])) {
            unset($config['conditions_config']);
        }

        return array_merge([
            'default' => (bool) $defaultActive,
            'conditions_config' => [],
        ], $config);
    }

    /**
     * @param string $name
     * @param Reference $reference
     * @param bool $default
     *
     * @return Definition
     */
    private function createFlagDefinition($name, Reference $reference, $default)
    {
        return new Definition(
            Flag::class,
            [
                (string) $name,
                $reference,
                (bool) $default
            ]
        );
    }

    /**
     * @param Definition $flag
     * @param array $config
     */
    private function processFlagConditions(Definition $flag, array $config)
    {
        foreach($config as $condition => $optionalValues) {
            $flag->addMethodCall(
                'addCondition',
                [
                    $condition,
                    $optionalValues
                ]
            );
        }
    }
}
