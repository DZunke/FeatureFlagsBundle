<?php

namespace DZunke\FeatureFlagsBundle\DependencyInjection;

use DZunke\FeatureFlagsBundle\Toggle\Flag;
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

        if (empty($config['flags'])) {
            return;
        }

        $taggedConditions = $container->findTaggedServiceIds('dz.feature_flags.toggle.condition');

        foreach ($config['flags'] as $name => $flagConfig) {

            $flagDefinition = new Definition('DZunke\FeatureFlagsBundle\Toggle\Flag', [$name, $flagConfig['default']]);

            if (!empty($flagConfig['conditions_config'])) {

                foreach ($taggedConditions as $tagService => $tagConfig) {
                    $tagConfig = reset($tagConfig);
                    if (in_array($tagConfig['alias'], array_keys($flagConfig['conditions_config']))) {
                        $flagDefinition->addMethodCall(
                            'addCondition',
                            [new Reference($tagService), $flagConfig['conditions_config'][$tagConfig['alias']]]
                        );
                    }
                }
            }

            $container->setDefinition('dz.feature_flags.toggle.flag.' . $name, $flagDefinition);

            $definition->addMethodCall('addFlag', [$flagDefinition]);

        }
    }

}
