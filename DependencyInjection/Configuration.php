<?php

namespace DZunke\FeatureFlagsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Validator\Constraints\Collection;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('d_zunke_feature_flags');

        $rootNode->children()
            ->booleanNode('default')
                ->info('the default state to return for non-existent features')
                ->defaultTrue()
            ->end()
        ->end();

        $rootNode->append($this->addFlags());

        return $treeBuilder;
    }

    /**
     * @return ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addFlags()
    {
        $treeBuilder = new TreeBuilder();
        $node        = $treeBuilder->root('flags');

        /** @var $connectionNode ArrayNodeDefinition */
        $mainNode = $node->useAttributeAsKey('feature')
            ->cannotBeEmpty()
            ->info('feature flags for the built system')
            ->prototype('array');

        $mainNode->children()
            ->booleanNode('default')
                ->info('general active state for the flag - if conditions used it would be irrelevant')
                ->defaultFalse()
            ->end()
            ->arrayNode('conditions_config')
                ->info('list of configured conditions which must be true to set this flag active')
                ->cannotBeEmpty()
                ->prototype('variable')
            ->end()
        ->end();

        return $node;
    }

}
