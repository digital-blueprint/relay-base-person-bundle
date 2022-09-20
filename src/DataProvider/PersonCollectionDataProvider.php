<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Dbp\Relay\BasePersonBundle\API\PersonProviderInterface;
use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\CoreBundle\LocalData\LocalData;
use Dbp\Relay\CoreBundle\Pagination\Pagination;
use Dbp\Relay\CoreBundle\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PersonCollectionDataProvider extends AbstractController implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public const MAX_NUM_ITEMS_PER_PAGE_DEFAULT = 50;

    /** @var PersonProviderInterface */
    private $personProvider;

    public function __construct(PersonProviderInterface $personProvider)
    {
        $this->personProvider = $personProvider;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Person::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): Paginator
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $filters = $context['filters'] ?? [];
        $options = [];

        if ($search = ($filters['search'] ?? null)) {
            $options[Person::SEARCH_PARAMETER_NAME] = $search;
        }

        LocalData::addOptions($options, $filters);
        Pagination::addOptions($options, $filters, self::MAX_NUM_ITEMS_PER_PAGE_DEFAULT);

        return $this->personProvider->getPersons($options);
    }
}
