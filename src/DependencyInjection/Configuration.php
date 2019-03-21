<?php

namespace Dmykos\IpStoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dmykos_ip_store');
        $rootNode
            ->children()
                ->scalarNode('store_driver')->defaultNull()->info('StoreDriverInterface implementation')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}