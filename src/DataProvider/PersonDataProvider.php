<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\DataProvider;

use Dbp\Relay\BasePersonBundle\API\PersonProviderInterface;
use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\CoreBundle\Rest\AbstractDataProvider;

/**
 * @extends AbstractDataProvider<Person>
 */
class PersonDataProvider extends AbstractDataProvider
{
    private const SEARCH_FILTER_NAME = 'search';

    public function __construct(private readonly PersonProviderInterface $personProvider)
    {
        parent::__construct();
    }

    protected function getItemById(string $id, array $filters = [], array $options = []): object
    {
        return $this->personProvider->getPerson($id, $options);
    }

    /**
     * @throws \Exception
     */
    protected function getPage(int $currentPageNumber, int $maxNumItemsPerPage, array $filters = [], array $options = []): array
    {
        if ($search = ($filters[self::SEARCH_FILTER_NAME] ?? null)) {
            $options[Person::SEARCH_PARAMETER_NAME] = $search;
        }

        return $this->personProvider->getPersons($currentPageNumber, $maxNumItemsPerPage, $options);
    }

    protected function isGrantedReadAccessToLocalDataAttribute(string $localDataAttributeName): bool
    {
        // override default local data attribute read access policy for the GET person ITEM operation:
        // current users are always granted read access to their own local data attributes
        // (even if their read access policy would evaluate to false)
        if (!$this->isCurrentOperationACollectionOperation()
            && $this->getCurrentUriVariables()[static::$identifierName] === $this->getUserIdentifier()) {
            return true;
        }

        return parent::isGrantedReadAccessToLocalDataAttribute($localDataAttributeName);
    }
}
