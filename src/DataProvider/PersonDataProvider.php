<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\DataProvider;

use Dbp\Relay\BasePersonBundle\API\PersonProviderInterface;
use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\CoreBundle\Rest\AbstractDataProvider;
use Dbp\Relay\CoreBundle\Rest\Options;
use Dbp\Relay\CoreBundle\Rest\Query\Filter\Filter;

class PersonDataProvider extends AbstractDataProvider
{
    /** @var PersonProviderInterface */
    private $personProvider;

    public function __construct(PersonProviderInterface $personProvider)
    {
        parent::__construct();

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

    protected function getItemById(string $id, array $filters = [], array $options = []): object
    {
        return $this->personProvider->getPerson($id, $options);
    }

    /**
     * @throws \Exception
     */
    protected function getPage(int $currentPageNumber, int $maxNumItemsPerPage, array $filters = [], array $options = []): array
    {
        if ($search = ($filters['search'] ?? null)) {
            $options[Person::SEARCH_PARAMETER_NAME] = $search;
        }

        $queryLocalParameter = $filters['queryLocal'] ?? null;
        if ($queryLocalParameter) {
            $this->handleDeprecateQueryLocalParameter($options, $queryLocalParameter);
        }

        $persons = $this->personProvider->getPersons($currentPageNumber, $maxNumItemsPerPage, $options);

        if ($queryLocalParameter) {
            $persons = $this->removeEntitiesWithForbiddenLocalData($options, $persons);
        }

        return $persons;
    }

    /**
     * @deprecated
     *
     * @throws \Exception
     */
    private function handleDeprecateQueryLocalParameter(array &$options, string $queryLocalParameter)
    {
        $queryLocalAttributes = [];
        $filter = Filter::create();
        foreach (explode(',', $queryLocalParameter) as $queryLocalAssignment) {
            $parts = explode(':', $queryLocalAssignment);
            if (count($parts) === 2) {
                $filter->getRootNode()->icontains($parts[0], $parts[1]);
                $queryLocalAttributes[] = $parts[0];
            }
        }
        Options::addFilter($options, $filter);
        $options['queryLocalAttributes'] = $queryLocalAttributes;
    }

    /**
     * @deprecated
     */
    private function removeEntitiesWithForbiddenLocalData(array $options, array $persons): array
    {
        foreach ($options['queryLocalAttributes'] as $queryLocalAttribute) {
            // since this is only for backwards compatibility and there is currently no application of a local query
            // whee the read policy depends on the person entity
            // we base the decision only on the user for increased performance
            if ($this->isGranted('@read-local-data:'.$queryLocalAttribute, new Person()) === false) {
                return [];
            }
        }

        return $persons;
    }
}
