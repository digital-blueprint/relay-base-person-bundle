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

    public function getPerson(string $id, array $options = []): Person;

    /**
     * Returns the Person matching the current user. Or null if there is no associated person
     * like when the client is another server.
     */
    public function getCurrentPerson(): ?Person;
}
