<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Dbp\Relay\BasePersonBundle\API\PersonProviderInterface;
use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\CoreBundle\Helpers\ArrayFullPaginator;
use Dbp\Relay\CoreBundle\LocalData\LocalData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PersonCollectionDataProvider extends AbstractController implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public const ITEMS_PER_PAGE = 250;

    private $api;

    public function __construct(PersonProviderInterface $api)
    {
        $this->api = $api;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Person::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): ArrayFullPaginator
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $perPage = self::ITEMS_PER_PAGE;
        $page = 1;
        $filters = $context['filters'] ?? [];
        $options = [];

        if ($search = ($filters['search'] ?? null)) {
            $options[Person::SEARCH_PARAMETER_NAME] = $search;
        }

        LocalData::addOptions($options, $filters);

        $persons = $this->api->getPersons($options);

        if (isset($filters['page'])) {
            $page = (int) $filters['page'];
        }

        if (isset($filters['perPage'])) {
            $perPage = (int) $filters['perPage'];
        }

        // TODO: do pagination via API
        return new ArrayFullPaginator($persons, $page, $perPage);
    }
}
