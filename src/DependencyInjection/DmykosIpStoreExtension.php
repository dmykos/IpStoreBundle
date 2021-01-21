<?php

namespace Dmykos\IpStoreBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DmykosIpStoreExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator( __DIR__ . '/../Resources/config' ));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);
        //d($config);
        if (null !== $config['store_driver']) {
            if($config['store_driver'] == "dmykos_ip_store.database_store_driver"){
                $container->setAlias('dmykos_ip_store.store_driver_interface', 'dmykos_ip_store.database_store_driver');

                $definition = $container->getDefinition('dmykos_ip_store.repository.database_store_repository');
                $arguments = $config['database'];
                $definition->setArgument(1, $arguments['table_name']);
                $definition->setArgument(2, $arguments['id_column_name']);
                $definition->setArgument(3, $arguments['id_column_value']);
                $definition->setArgument(4, $arguments['key_column_name']);
            } else{
                $container->setAlias('dmykos_ip_store.store_driver_interface', $config['store_driver']);
            }
        }
    }
}
