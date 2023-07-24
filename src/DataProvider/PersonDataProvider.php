<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\DataProvider;

use Dbp\Relay\BasePersonBundle\API\PersonProviderInterface;
use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\CoreBundle\Rest\AbstractDataProvider;
use Dbp\Relay\CoreBundle\Rest\Options;
use Dbp\Relay\CoreBundle\Rest\Query\Filter\Filter;
use Dbp\Relay\CoreBundle\Rest\Query\Filter\FilterTreeBuilder;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PersonDataProvider extends AbstractDataProvider
{
    private const LOCAL_DATA_BASE_PATH = 'localData.';

    /** @var PersonProviderInterface */
    private $personProvider;

    /**
     * @deprecated this is for backwards compatibility of the queryLocal parameter
     *
     * @var array
     */
    private $definedLocalDataAttributes = [];

    public function __construct(PersonProviderInterface $personProvider)
    {
        parent::__construct();

        $this->personProvider = $personProvider;
    }

    public function setConfig(array $config)
    {
        parent::setConfig($config);

        // deprecated: this is for backwards compatibility of the queryLocal parameter
        foreach ($config['local_data'] ?? [] as $localDataConfigEntry) {
            $this->definedLocalDataAttributes[] = $localDataConfigEntry['local_data_attribute'];
        }
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
     * @throws \Exception
     *
     * @deprecated query parameter localQuery is deprecated since core bundle version 1.1.15. Clients should use the
     *             'filter' query parameter introduced in version 1.1.15 instead.
     */
    private function handleDeprecateQueryLocalParameter(array &$options, string $queryLocalParameter)
    {
        $queryLocalAttributes = [];
        $filterTreeBuilder = FilterTreeBuilder::create();
        foreach (explode(',', $queryLocalParameter) as $queryLocalAssignment) {
            $parts = explode(':', $queryLocalAssignment);
            if (count($parts) === 2) {
                if (!in_array($parts[0], $this->definedLocalDataAttributes, true)) {
                    throw new BadRequestException('local data attribute undefined');
                }
                $filterTreeBuilder->iContains(self::LOCAL_DATA_BASE_PATH.$parts[0], $parts[1]);
                $queryLocalAttributes[] = $parts[0];
            } else {
                throw new BadRequestException('invalid localQuery format');
            }
        }
        Options::addFilter($options, $filterTreeBuilder->createFilter());
        $options['queryLocalAttributes'] = $queryLocalAttributes;
    }

    /**
     * @deprecated query parameter localQuery is deprecated since core bundle version 1.1.15. Clients should use the
     *             'filter' query parameter introduced in version 1.1.15 instead.
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
