<?php

declare(strict_types=1);

namespace Dbp\Relay\BaseBundle\TestUtils;

use ApiPlatform\Core\Exception\ItemNotFoundException;
use Dbp\Relay\BaseBundle\API\PersonProviderInterface;
use Dbp\Relay\BaseBundle\Entity\Person;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DummyPersonProvider implements PersonProviderInterface
{
    /* @var Person */
    private $person;

    public function __construct($person)
    {
        $this->person = $person;
    }

    public function getPersons(array $filters): array
    {
        return [$this->person];
    }

    public function getPerson(string $id): Person
    {
        if ($id !== $this->person->getIdentifier()) {
            throw new NotFoundHttpException();
        }

        return $this->person;
    }

    public function getCurrentPerson(): Person
    {
        return $this->person;
    }

    public function getPersonForExternalService(string $service, string $serviceID): Person
    {
        throw new ItemNotFoundException();
    }

    public function getPersonsByNameAndBirthDate(string $givenName, string $familyName, string $birthDate): array
    {
        return [];
    }

    public function setCurrentIdentifier(string $identifier): void
    {
        $this->person->setIdentifier($identifier);
    }

    public function getRolesForCurrentPerson(): array
    {
        return $this->person->getRoles();
    }

    public function setRolesForCurrentPerson(array $roles): void
    {
        $this->person->setRoles($roles);
    }
}
