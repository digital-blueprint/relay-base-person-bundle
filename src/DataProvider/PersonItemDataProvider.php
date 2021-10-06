<?php

declare(strict_types=1);

namespace Dbp\Relay\BaseBundle\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Dbp\Relay\BaseBundle\API\PersonProviderInterface;
use Dbp\Relay\BaseBundle\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PersonItemDataProvider extends AbstractController implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $api;

    public function __construct(PersonProviderInterface $api)
    {
        $this->api = $api;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Person::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Person
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $person = null;
        $api = $this->api;
        $person = $api->getPerson($id);

        return $person;
    }
}
