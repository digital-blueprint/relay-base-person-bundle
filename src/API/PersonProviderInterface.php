<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\API;

use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\CoreBundle\Exception\ApiError;

interface PersonProviderInterface
{
    /**
     * Throws an HTTP_NOT_FOUND exception if no person with the given ID can be found.
     *
     * @param array $options Available options:
     *                       * LocalData::INCLUDE_PARAMETER_NAME
     *
     * @throws ApiError
     */
    public function getPerson(string $identifier, array $options = []): Person;

    /**
     * @param array $options Available options:
     *                       * Person::SEARCH_PARAMETER_NAME (whitespace separated list of search terms to perform a partial case-insensitive text search on person's full name)
     *                       * LocalData::INCLUDE_PARAMETER_NAME
     *                       * LocalData::QUERY_PARAMETER_NAME
     *
     * @return Person[]
     *
     * @throws ApiError
     */
    public function getPersons(int $currentPageNumber, int $maxNumItemsPerPage, array $options = []): array;

    /**
     * Returns the Person matching the current user. Or null if there is no associated person
     * like when the client is another server. Throws an HTTP_NOT_FOUND exception if no person is found for the current user.
     *
     * @param array $options Available options:
     *                       * LocalData::INCLUDE_PARAMETER_NAME
     *
     * @throws ApiError
     */
    public function getCurrentPerson(array $options = []): ?Person;
}
