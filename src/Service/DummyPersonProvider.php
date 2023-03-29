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

    public function getPersons(int $currentPageNumber, int $maxNumItemsPerPage, array $options = []): array
    {
        $persons = [];
        $currentPerson = $this->getCurrentPerson();
        if ($currentPerson !== null) {
            $persons[] = $currentPerson;
        }

        return $persons;
    }

    public function getPerson(string $id, array $options = []): Person
    {
        $person = new Person();
        $person->setIdentifier($id);
        $person->setGivenName('John');
        $person->setFamilyName('Doe');

        return $person;
    }

    public function getCurrentPerson(): ?Person
    {
        if ($this->currentIdentifier === null) {
            return null;
        }

        return $this->getPerson($this->currentIdentifier);
    }

    public function setCurrentIdentifier(string $identifier): void
    {
        $this->currentIdentifier = $identifier;
    }
}
