<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\TestUtils;

use Dbp\Relay\BasePersonBundle\API\PersonProviderInterface;
use Dbp\Relay\BasePersonBundle\Entity\Person;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DummyPersonProvider implements PersonProviderInterface
{
    /* @var Person */
    private $person;

    public function __construct($person)
    {
        $this->person = $person;
    }

    public function getPersons(int $currentPageNumber, int $maxNumItemsPerPage, array $options = []): array
    {
        return [$this->person];
    }

    public function getPerson(string $id, array $options = []): Person
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

    public function setCurrentIdentifier(string $identifier): void
    {
        $this->person->setIdentifier($identifier);
    }
}
