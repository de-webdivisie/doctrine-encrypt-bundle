<?php

declare(strict_types=1);

namespace Wdnl\DoctrineEncryptBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Used to load configuration and services definition file.
 */
class WdnlDoctrineEncryptExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(
            'wdnl_doctrine_encrypt.encryption_key',
            $config['encryption_key']
        );

        $container->setParameter(
            'wdnl_doctrine_encrypt.encryption_salt',
            $config['encryption_salt']
        );

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yml');
    }

    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @return Configuration
     */
    public function getConfiguration(
        array $configs,
        ContainerBuilder $container
    ) {
        return new Configuration();
    }
}
