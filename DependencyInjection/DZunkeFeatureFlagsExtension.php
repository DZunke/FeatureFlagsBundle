<?php

namespace DZunke\FeatureFlagsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DZunkeFeatureFlagsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config        = $this->processConfiguration($configuration, $configs);

        $container->setParameter('d_zunke_feature_flags.config', $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $this->configureToogle($config, $container);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    protected function configureToogle(array $config, ContainerBuilder $container)
    {
        $definition = $container->getDefinition('dz.feature_flags.toggle');
        $definition->addMethodCall('setDefaultState', [$config['default']]);

        $this->fillConditionsBag($container);

        foreach ($config['flags'] as $name => $flagConfig) {
            $flagDefinition = new Definition(
                'DZunke\FeatureFlagsBundle\Toggle\Flag',
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

    protected function fillConditionsBag(ContainerBuilder $container)
    {
        $taggedConditions = $container->findTaggedServiceIds('dz.feature_flags.toggle.condition');
        if (!empty($taggedConditions)) {
            $containerBag = $container->getDefinition('dz.feature_flags.conditions_bag');

            foreach ($taggedConditions as $service => $options) {

                $options = reset($options);
                $containerBag->addMethodCall('set', [$options['alias'], new Reference($service)]);

            }
        }
    }

}
