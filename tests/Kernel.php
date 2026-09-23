<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Tests;

use Dbp\Relay\BasePersonBundle\DbpRelayBasePersonBundle;
use Dbp\Relay\CoreBundle\TestUtils\CoreTestKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use CoreTestKernelTrait;

    protected function registerAdditionalBundles(): iterable
    {
        yield new DbpRelayBasePersonBundle();
    }

    protected function configureAdditionalContainer(ContainerConfigurator $container): void
    {
        $container->import('@DbpRelayBasePersonBundle/Resources/config/services_test.yaml');
        $container->extension('dbp_relay_base_person', [
            'authorization' => [
                'roles' => [
                    'ROLE_READER' => 'user.get("MAY_READ")',
                ],
            ],
        ]);
    }
}
