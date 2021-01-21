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
            /*->beforeNormalization()
            ->ifTrue(function($v) {
                // $v contains the raw configuration values
                return !isset($v['store_driver']) || $v['store_driver'] !== "dmykos_ip_store.database_store_driver";
            })
            ->then(function($v) {
                unset($v['positive_value']);
                return $v;
            })
            ->end()*/
            ->children()
                ->scalarNode('store_driver')
                    ->defaultValue('dmykos_ip_store.database_store_driver')
                    ->info('StoreDriverInterface implementation (dmykos_ip_store.database_store_driver or you custom service)')
                ->end()
                ->arrayNode('database')
                    ->children()
                        ->scalarNode('table_name')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('id_column_name')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('id_column_value')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('key_column_name')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                    ->info('Database settings for dmykos_ip_store.database_store_driver')
                ->end()
            ->end()
            ->validate()
                ->ifTrue(function($v) {
                    // $v contains the raw configuration values
                    if($v['store_driver'] === "dmykos_ip_store.database_store_driver"){
                        if(!isset($v['database'])){
                            return true;
                        }
                    }
                    return false;
                })
                ->thenInvalid("database is required if store_driver equals to dmykos_ip_store.database_store_driver")
            ->end()
            ->validate()
                ->ifTrue(function($v) {
                    if($v['store_driver'] !== "dmykos_ip_store.database_store_driver"){
                        if(isset($v['database'])){
                            return true;
                        }
                    }
                    return false;
                })
                ->thenInvalid("database is required only if store_driver equals to dmykos_ip_store.database_store_driver")
            ->end()
        ;

        return $treeBuilder;
    }
}