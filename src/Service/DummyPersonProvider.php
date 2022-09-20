<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Service;

use Dbp\Relay\BasePersonBundle\API\PersonProviderInterface;
use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\CoreBundle\Pagination\FullPaginator;
use Dbp\Relay\CoreBundle\Pagination\Pagination;
use Dbp\Relay\CoreBundle\Pagination\Paginator;

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

    public function getPersons(array $options): Paginator
    {
        $persons = [];
        $currentPerson = $this->getCurrentPerson();
        if ($currentPerson !== null) {
            $persons[] = $currentPerson;
        }

        return new FullPaginator($persons, 1, Pagination::MAX_NUM_ITEMS_PER_PAGE_DEFAULT, count($persons));
    }

    public function getPerson(string $id, array $options = []): Person
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

    public function setCurrentIdentifier(string $identifier): void
    {
        $this->currentIdentifier = $identifier;
    }
}
