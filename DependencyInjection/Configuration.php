<?php

namespace DZunke\FeatureFlagsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('d_zunke_feature_flags');
        $rootNode    = $treeBuilder->getRootNode();

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
        $treeBuilder = new TreeBuilder('flags');
        $node        = $treeBuilder->getRootNode();

        /** @var $connectionNode ArrayNodeDefinition */
        $mainNode = $node->useAttributeAsKey('feature')
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->info('feature flags for the built system')
            ->prototype('array');

        $mainNode->children()
            ->booleanNode('default')
                ->info('general active state for the flag - if conditions used it would be irrelevant')
                ->defaultFalse()
            ->end()
            ->arrayNode('conditions_config')
                ->requiresAtLeastOneElement()
                ->info('list of configured conditions which must be true to set this flag active')
                ->prototype('variable')
            ->end()
        ->end();

        return $node;
    }

}
