<?php

namespace Dlin\Bundle\KeenBundle\DependencyInjection;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DlinKeenExtension extends Extension {
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');


        $container->setParameter(
            'dlin.keen_service.project_id',
            isset($config['project_id'])?$config['project_id']:''
        );
        $container->setParameter(
            'dlin.keen_service.write_key',
            isset($config['write_key'])?$config['write_key']:''
        );
        $container->setParameter(
            'dlin.keen_service.read_key',
            isset($config['read_key'])?$config['read_key']:''
        );
    }
}