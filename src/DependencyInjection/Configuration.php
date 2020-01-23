<?php

namespace JGI\ArbetsformedlingenAdsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('arbetsformedlingen_ads');

        $loggersNode = new ArrayNodeDefinition('loggers');
        $loggersNode->prototype('scalar')->end();

        $root->children()
            ->scalarNode('http_client')->isRequired()->end()
            ->booleanNode('test_environment')->defaultFalse()->end()
            ->append($loggersNode)
            ->end();

        return $treeBuilder;
    }
}
