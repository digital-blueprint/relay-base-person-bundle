<?php

declare(strict_types=1);

namespace Dbp\Relay\BaseBundle\API;

use Dbp\Relay\BaseBundle\Entity\Person;

interface PersonProviderInterface
{
    /**
     * @return Person[]
     */
    public function getPersons(array $filters): array;

    /**
     * @return Person[]
     */
    public function getPersonsByNameAndBirthDate(string $givenName, string $familyName, string $birthDate): array;

    public function getPerson(string $id): Person;

    public function getPersonForExternalService(string $service, string $serviceID): Person;

    /**
     * Returns the Person matching the current user. Or null if there is no associated person
     * like when the client is another server.
     */
    public function getCurrentPerson(): ?Person;
}
