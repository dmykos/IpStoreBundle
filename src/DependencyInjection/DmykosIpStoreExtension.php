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

        if (null !== $config['store_driver']) {
            $container->setAlias('dmykos_ip_store.store_driver_interface', $config['store_driver']);
        }
    }

 /*   public function prepend(ContainerBuilder $container)
    {
        $doctrineConfig = [];
        $doctrineConfig['orm']['mappings'][] = array(
            'name' => 'DmykosIpStoreBundle',
            'is_bundle' => true,
            'type' => 'annotation',
            'prefix' => 'Dmykos\IpStoreBundle\Entity',
            'dir' => 'Entity'
        );
        $container->prependExtensionConfig('doctrine', $doctrineConfig);


    }*/
}
