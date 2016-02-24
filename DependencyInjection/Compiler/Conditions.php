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
        if (true !== $container->getParameterBag()->has('d_zunke_feature_flags.config')) {
            throw new \RuntimeException('No parameters with name \'d_zunke_feature_flags.config\' could be found');
        }

        $config = $container->getParameterBag()->get('d_zunke_feature_flags.config');

        $this->configureToggle($config, $container);
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     */
    private function configureToggle(array $config, ContainerBuilder $container)
    {
        $definition = $container->getDefinition('dz.feature_flags.toggle');
        $definition->addMethodCall('setDefaultState', [$config['default']]);

        $this->fillConditionsBag($container);

        foreach ($config['flags'] as $name => $flagConfig) {
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
