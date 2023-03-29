<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Service;

use Dbp\Relay\BasePersonBundle\API\PersonProviderInterface;
use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\CoreBundle\Exception\ApiError;
use Symfony\Component\HttpFoundation\Response;

class DummyPersonProvider implements PersonProviderInterface
{
    /**
     * @var Person|null
     */
    private $currentPerson;

    public function getPersons(int $currentPageNumber, int $maxNumItemsPerPage, array $options = []): array
    {
        $persons = [];
        if ($this->currentPerson !== null) {
            $persons[] = $this->currentPerson;
        }

        return $persons;
    }

    public function getPerson(string $id, array $options = []): Person
    {
        if ($this->currentPerson === null || $id !== $this->currentPerson->getIdentifier()) {
            throw ApiError::withDetails(Response::HTTP_NOT_FOUND);
        }

        return $this->currentPerson;
    }

    public function getCurrentPerson(array $options = []): ?Person
    {
        return $this->currentPerson;
    }

    public function setCurrentPerson(Person $person): void
    {
        $this->currentPerson = $person;
    }
}
