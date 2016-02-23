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

        if (1 <= count($configs)) {
            return;
        }

        $config = reset($configs);

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

            $flagDefinition = new Definition(
                Flag::class, //'DZunke\FeatureFlagsBundle\Toggle\Flag',
                [
                    $name,
                    new Reference('dz.feature_flags.conditions_bag'),
                    $flagConfig['default']
                ]
            );

            foreach ($flagConfig['conditions_config'] as $condition => $config) {
                $flagDefinition->addMethodCall(
                    'addCondition',
                    [
                        $condition,
                        $config
                    ]
                );
            }

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
}