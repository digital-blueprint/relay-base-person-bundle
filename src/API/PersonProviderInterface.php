<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\API;

use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\CoreBundle\Exception\ApiError;

interface PersonProviderInterface
{
    /**
     * @param array $options Available options:
     *                       * Person::SEARCH_PARAMETER_NAME (whitespace separated list of search terms to perform a partial case-insensitive text search on person's full name)
     *                       * LocalData::INCLUDE_PARAMETER_NAME
     *                       * LocalData::QUERY_PARAMETER_NAME
     *
     * @throws ApiError
     *
     * @return Person[]
     */
    public function getPersons(array $options): array;

    /**
     * @param array $options Available options:
     *                       * LocalData::INCLUDE_PARAMETER_NAME
     *
     * @throws ApiError
     */
    public function getPerson(string $id, array $options = []): Person;

    /**
     * Returns the Person matching the current user. Or null if there is no associated person
     * like when the client is another server.
     */
    public function getCurrentPerson(): ?Person;
}
