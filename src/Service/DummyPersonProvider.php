<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Service;

use Dbp\Relay\BasePersonBundle\API\PersonProviderInterface;
use Dbp\Relay\BasePersonBundle\Entity\Person;

class DummyPersonProvider implements PersonProviderInterface
{
    /**
     * @var string|null
     */
    private $currentIdentifier;

    public function __construct()
    {
        $this->currentIdentifier = null;
    }

    public function getPersons(array $filters): array
    {
        $person = $this->getCurrentPerson();
        if ($person !== null) {
            return [$person];
        }

        return [];
    }

    public function getPerson(string $id): Person
    {
        $person = new Person();
        $person->setIdentifier($id);
        $person->setGivenName('John');
        $person->setFamilyName('Doe');
        $person->setEmail('john.doe@example.com');

        return $person;
    }

    public function getCurrentPerson(): ?Person
    {
        if ($this->currentIdentifier === null) {
            return null;
        }

        return $this->getPerson($this->currentIdentifier);
    }

    public function getPersonForExternalService(string $service, string $serviceID): Person
    {
        return new Person();
    }

    public function setCurrentIdentifier(string $identifier): void
    {
        $this->currentIdentifier = $identifier;
    }
}
