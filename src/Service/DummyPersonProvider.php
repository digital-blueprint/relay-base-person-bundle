<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Service;

use Dbp\Relay\BasePersonBundle\API\PersonProviderInterface;
use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\CoreBundle\Exception\ApiError;
use Dbp\Relay\CoreBundle\Rest\Options;
use Dbp\Relay\CoreBundle\Rest\Query\Pagination\Pagination;
use Symfony\Component\HttpFoundation\Response;

class DummyPersonProvider implements PersonProviderInterface
{
    /**
     * @var array<string, array>
     */
    private array $persons = [];
    private ?string $currentPersonIdentifier = null;

    /**
     * @return Person[]
     */
    public function getPersons(int $currentPageNumber, int $maxNumItemsPerPage, array $options = []): array
    {
        $persons = [];
        foreach (array_slice($this->persons,
            Pagination::getFirstItemIndex($currentPageNumber, $maxNumItemsPerPage), $maxNumItemsPerPage) as $personData) {
            $persons[] = $this->getPerson($personData['identifier'], $options);
        }

        return $persons;
    }

    public function getPerson(string $identifier, array $options = []): Person
    {
        $personData = $this->persons[$identifier] ?? null;
        if ($personData === null) {
            throw ApiError::withDetails(Response::HTTP_NOT_FOUND,
                sprintf("Person with ID '%s' does not exist", $identifier));
        }

        $person = new Person();
        $person->setIdentifier($personData['identifier']);
        $person->setGivenName($personData['givenName']);
        $person->setFamilyName($personData['familyName']);

        foreach (Options::getLocalDataAttributes($options) as $localDataAttribute) {
            if ($localDataAttributeValue = $personData['localData'][$localDataAttribute] ?? null) {
                $person->setLocalDataValue($localDataAttribute, $localDataAttributeValue);
            } else {
                throw ApiError::withDetails(Response::HTTP_BAD_REQUEST,
                    'local data attribute \''.$localDataAttribute.'\' undefined');
            }
        }

        return $person;
    }

    public function getCurrentPerson(array $options = []): ?Person
    {
        return $this->currentPersonIdentifier !== null ?
            $this->getPerson($this->currentPersonIdentifier, $options) : null;
    }

    /**
     * @deprecated use addPerson followed by setCurrentPersonIdentifier instead
     */
    public function setCurrentPerson(Person $person): void
    {
        if ($person->getIdentifier()) {
            $this->addPerson($this->currentPersonIdentifier, $person->getGivenName(), $person->getFamilyName());
            $this->currentPersonIdentifier = $person->getIdentifier();
        }
    }

    public function addPerson(string $identifier, string $givenName, string $familyName, array $localDataAttributes = []): void
    {
        $this->persons[$identifier] = [
            'identifier' => $identifier,
            'givenName' => $givenName,
            'familyName' => $familyName,
            'localData' => $localDataAttributes,
        ];
    }

    public function setCurrentPersonIdentifier(?string $identifier): void
    {
        if ($identifier !== null && !isset($this->persons[$identifier])) {
            throw new \RuntimeException(sprintf("person with identifier '%s' does not exist", $identifier));
        }
        $this->currentPersonIdentifier = $identifier;
    }
}
