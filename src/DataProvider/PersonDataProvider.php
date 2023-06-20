<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\DataProvider;

use Dbp\Relay\BasePersonBundle\API\PersonProviderInterface;
use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\CoreBundle\ApiPlatform\State\AbstractStateProvider;

class PersonDataProvider extends AbstractStateProvider
{
    /** @var PersonProviderInterface */
    private $personProvider;

    public function __construct(PersonProviderInterface $personProvider)
    {
        $this->personProvider = $personProvider;
    }

    protected function getResourceClass(): string
    {
        return Person::class;
    }

    protected function isUserGrantedOperationAccess(int $operation): bool
    {
        return $this->isAuthenticated();
    }

    protected function getItemById($id, array $filters = [], array $options = []): object
    {
        return $this->personProvider->getPerson($id, $options);
    }

    protected function getPage(int $currentPageNumber, int $maxNumItemsPerPage, array $filters = [], array $options = []): array
    {
        if ($search = ($filters['search'] ?? null)) {
            $options[Person::SEARCH_PARAMETER_NAME] = $search;
        }

        return $this->personProvider->getPersons($currentPageNumber, $maxNumItemsPerPage, $options);
    }
}
