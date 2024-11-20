<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\TestUtils;

use Dbp\Relay\BasePersonBundle\Service\DummyPersonProvider;
use Dbp\Relay\CoreBundle\TestUtils\TestClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait TestPersonTrait
{
    public function withCurrentPerson(ContainerInterface $container,
        string $userIdentifier = TestClient::TEST_USER_IDENTIFIER,
        string $givenName = 'Jane',
        string $familyName = 'Doe',
        array $localDataAttributes = []): void
    {
        $personProvider = $container->get(DummyPersonProvider::class);
        $personProvider->addPerson($userIdentifier, $givenName, $familyName, $localDataAttributes);
        $personProvider->setCurrentPersonIdentifier($userIdentifier);
    }

    public function withPerson(ContainerInterface $container,
        string $userIdentifier = TestClient::TEST_USER_IDENTIFIER,
        string $givenName = 'John',
        string $familyName = 'Doe',
        array $localDataAttributes = []): void
    {
        $personProvider = $container->get(DummyPersonProvider::class);
        $personProvider->addPerson($userIdentifier, $givenName, $familyName, $localDataAttributes);
    }
}
