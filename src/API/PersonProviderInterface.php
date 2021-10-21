<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\API;

use Dbp\Relay\BasePersonBundle\Entity\Person;

interface PersonProviderInterface
{
    /**
     * @param array $filters $filters['search'] can be a string to search for people (e.g. part of the name)
     *
     * @return Person[]
     */
    public function getPersons(array $filters): array;

    /**
     * @return Person[]
     */
    public function getPersonsByNameAndBirthDate(string $givenName, string $familyName, string $birthDate): array;

    public function getPerson(string $id): Person;

    /**
     * This is only used by external services (e.g. the alma bundle) to translate external persons to internal persons.
     *
     * @param string $service   identifies the service that wants to fetch a person
     * @param string $serviceID identifies person by an external id
     */
    public function getPersonForExternalService(string $service, string $serviceID): Person;

    /**
     * Returns the Person matching the current user. Or null if there is no associated person
     * like when the client is another server.
     */
    public function getCurrentPerson(): ?Person;
}
