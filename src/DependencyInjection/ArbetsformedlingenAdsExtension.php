<?php

namespace JGI\ArbetsformedlingenAdsBundle\DependencyInjection;

use JGI\ArbetsformedlingenAds\Client;
use JGI\ArbetsformedlingenAdsBundle\Listener\LoggerListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class ArbetsformedlingenAdsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $config);

        $container->getDefinition(Client::class)->addMethodCall('setHttpClient', [new Reference($config['http_client'])]);
        $container->getDefinition(Client::class)->addMethodCall('setEventDispatcher', [new Reference(EventDispatcherInterface::class)]);
        $container->getDefinition(Client::class)->addMethodCall('setTestEnvironment', [$config['test_environment']]);
        foreach ($config['loggers'] as $logger) {
            $container->getDefinition(LoggerListener::class)->addMethodCall('addLogger', [new Reference($logger)]);
        }
    }
}
