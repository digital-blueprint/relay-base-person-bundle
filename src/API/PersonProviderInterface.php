<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\API;

use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\CoreBundle\Exception\ApiError;

interface PersonProviderInterface
{
    /**
     * Returns the person with the given identifier or
     * throws an HTTP_NOT_FOUND exception if no person with the given ID can be found.
     * NOTE: Implementors must consider the FILTER option, even for the item operation,
     * since the current user might not have read access to the requested person.
     *
     * @param array $options Available options are:
     *
     * @see Dbp\Relay\CoreBundle\Rest\Options::LOCAL_DATA_ATTRIBUTES
     * @see Dbp\Relay\CoreBundle\Rest\Options::FILTER
     *
     * @throws ApiError
     */
    public function getPerson(string $identifier, array $options = []): Person;

    /**
     * @param array $options Available options:
     *
     * @see Person::SEARCH_PARAMETER_NAME (whitespace separated list of search terms to perform a partial case-insensitive text search on person's full name)
     * @see Dbp\Relay\CoreBundle\Rest\Options::LOCAL_DATA_ATTRIBUTES
     * @see Dbp\Relay\CoreBundle\Rest\Options::FILTER
     * @see Dbp\Relay\CoreBundle\Rest\Options::SORT
     *
     * @return Person[]
     *
     * @throws ApiError
     */
    public function getPersons(int $currentPageNumber, int $maxNumItemsPerPage, array $options = []): array;

    /**
     * Returns the identifier of the person representing the current user, or null if there is none,
     * e.g., when in a client credentials flow, i.e., the authorized party does not represent a person.
     *
     * @throws ApiError
     */
    public function getCurrentPersonIdentifier(): ?string;

    /**
     * Returns the person representing the current user, or null if there is none,
     * e.g., when in a client credentials flow, i.e., the authorized party does not represent a person.
     *
     * @param array $options Available options:
     *
     * @see Dbp\Relay\CoreBundle\Rest\Options::LOCAL_DATA_ATTRIBUTES
     *
     * @throws ApiError
     */
    public function getCurrentPerson(array $options = []): ?Person;
}
