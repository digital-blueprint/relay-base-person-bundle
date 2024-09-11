<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\DependencyInjection;

use Dbp\Relay\BasePersonBundle\DataProvider\PersonDataProvider;
use Dbp\Relay\CoreBundle\Extension\ExtensionTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class DbpRelayBasePersonExtension extends ConfigurableExtension
{
    use ExtensionTrait;

    /**
     * @throws \Exception
     */
    public function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $this->addResourceClassDirectory($container, __DIR__.'/../Entity');

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yaml');

        $definition = $container->getDefinition(PersonDataProvider::class);
        $definition->addMethodCall('setConfig', [$mergedConfig]);
    }
}
