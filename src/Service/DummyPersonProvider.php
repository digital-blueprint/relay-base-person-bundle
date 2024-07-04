<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Service;

use Dbp\Relay\BasePersonBundle\API\PersonProviderInterface;
use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\CoreBundle\Exception\ApiError;
use Dbp\Relay\CoreBundle\Rest\Options;
use Symfony\Component\HttpFoundation\Response;

class DummyPersonProvider implements PersonProviderInterface
{
    private ?Person $currentPerson;

    /**
     * @return Person[]
     */
    public function getPersons(int $currentPageNumber, int $maxNumItemsPerPage, array $options = []): array
    {
        $persons = [];
        $currentPerson = $this->getCurrentPerson();
        if ($currentPerson !== null && $currentPageNumber === 1 && $maxNumItemsPerPage > 0) {
            $persons[] = $currentPerson;
        }

        return $persons;
    }

    public function getPerson(string $id, array $options = []): Person
    {
        if ($this->currentPerson === null || $id !== $this->currentPerson->getIdentifier()) {
            throw ApiError::withDetails(Response::HTTP_NOT_FOUND);
        }

        $person = new Person();
        $person->setIdentifier($this->currentPerson->getIdentifier());
        $person->setGivenName($this->currentPerson->getGivenName());
        $person->setFamilyName($this->currentPerson->getFamilyName());

        // mirror local data attributes
        foreach (Options::getLocalDataAttributes($options) as $localDataAttribute) {
            $person->setLocalDataValue($localDataAttribute, $localDataAttribute);
        }

        return $person;
    }

    public function getCurrentPerson(array $options = []): ?Person
    {
        return $this->currentPerson !== null ?
            $this->getPerson($this->currentPerson->getIdentifier(), $options) : null;
    }

    public function setCurrentPerson(Person $person): void
    {
        $this->currentPerson = $person;
    }
}
