<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Tests;

use Dbp\Relay\BasePersonBundle\Service\DummyPersonProvider;
use Dbp\Relay\CoreBundle\Rest\Options;
use PHPUnit\Framework\TestCase;

class DummyPersonProviderTest extends TestCase
{
    private ?DummyPersonProvider $personProvider = null;

    protected function setUp(): void
    {
        $this->personProvider = new DummyPersonProvider();
        $this->personProvider->addPerson('jm', 'Jane', 'Moe', ['title' => 'Queen', 'city' => 'London']);
        $this->personProvider->addPerson('jd', 'John', 'Doe', ['title' => 'Dr.']);
        $this->personProvider->addPerson('fb', 'Foo', 'Bar', ['title' => 'Lord']);
        $this->personProvider->setCurrentPersonIdentifier('jd');
    }

    public function testGetCurrentPersonIdentifier(): void
    {
        $this->assertSame('jd', $this->personProvider->getCurrentPersonIdentifier());
    }

    public function testGetCurrentPerson(): void
    {
        $currentPerson = $this->personProvider->getCurrentPerson();
        $this->assertSame('jd', $currentPerson->getIdentifier());
        $this->assertSame('John', $currentPerson->getGivenName());
        $this->assertSame('Doe', $currentPerson->getFamilyName());
        $this->assertNull($currentPerson->getLocalDataValue('title'));

        $options = [];
        $currentPerson = $this->personProvider->getCurrentPerson(
            Options::requestLocalDataAttributes($options, ['title']));
        $this->assertSame('jd', $currentPerson->getIdentifier());
        $this->assertSame('John', $currentPerson->getGivenName());
        $this->assertSame('Doe', $currentPerson->getFamilyName());
        $this->assertSame('Dr.', $currentPerson->getLocalDataValue('title'));
    }

    public function testGetPerson(): void
    {
        $person = $this->personProvider->getPerson('jm');
        $this->assertSame('jm', $person->getIdentifier());
        $this->assertSame('Jane', $person->getGivenName());
        $this->assertSame('Moe', $person->getFamilyName());
        $this->assertNull($person->getLocalDataValue('city'));
        $this->assertNull($person->getLocalDataValue('title'));

        $options = [];
        $person = $this->personProvider->getPerson('jm',
            Options::requestLocalDataAttributes($options, ['city', 'title']));
        $this->assertSame('jm', $person->getIdentifier());
        $this->assertSame('Jane', $person->getGivenName());
        $this->assertSame('Moe', $person->getFamilyName());
        $this->assertSame('London', $person->getLocalDataValue('city'));
        $this->assertSame('Queen', $person->getLocalDataValue('title'));
    }

    public function testGetPersons(): void
    {
        $persons = $this->personProvider->getPersons(1, 10);
        $this->assertCount(3, $persons);
        $this->assertSame('jm', $persons[0]->getIdentifier());
        $this->assertSame('Jane', $persons[0]->getGivenName());
        $this->assertSame('Moe', $persons[0]->getFamilyName());
        $this->assertNull($persons[0]->getLocalDataValue('title'));
        $this->assertSame('jd', $persons[1]->getIdentifier());
        $this->assertSame('John', $persons[1]->getGivenName());
        $this->assertSame('Doe', $persons[1]->getFamilyName());
        $this->assertNull($persons[1]->getLocalDataValue('title'));
        $this->assertSame('fb', $persons[2]->getIdentifier());
        $this->assertSame('Foo', $persons[2]->getGivenName());
        $this->assertSame('Bar', $persons[2]->getFamilyName());
        $this->assertNull($persons[2]->getLocalDataValue('title'));

        $options = [];
        $persons = $this->personProvider->getPersons(1, 10,
            Options::requestLocalDataAttributes($options, ['title']));
        $this->assertCount(3, $persons);
        $this->assertSame('jm', $persons[0]->getIdentifier());
        $this->assertSame('Jane', $persons[0]->getGivenName());
        $this->assertSame('Moe', $persons[0]->getFamilyName());
        $this->assertSame('Queen', $persons[0]->getLocalDataValue('title'));
        $this->assertSame('jd', $persons[1]->getIdentifier());
        $this->assertSame('John', $persons[1]->getGivenName());
        $this->assertSame('Doe', $persons[1]->getFamilyName());
        $this->assertSame('Dr.', $persons[1]->getLocalDataValue('title'));
        $this->assertSame('fb', $persons[2]->getIdentifier());
        $this->assertSame('Foo', $persons[2]->getGivenName());
        $this->assertSame('Bar', $persons[2]->getFamilyName());
        $this->assertSame('Lord', $persons[2]->getLocalDataValue('title'));
    }

    public function testGetPersonsPaginated(): void
    {
        $persons = $this->personProvider->getPersons(1, 2);
        $this->assertCount(2, $persons);
        $this->assertSame('jm', $persons[0]->getIdentifier());
        $this->assertSame('Jane', $persons[0]->getGivenName());
        $this->assertSame('Moe', $persons[0]->getFamilyName());
        $this->assertNull($persons[0]->getLocalDataValue('title'));
        $this->assertSame('jd', $persons[1]->getIdentifier());
        $this->assertSame('John', $persons[1]->getGivenName());
        $this->assertSame('Doe', $persons[1]->getFamilyName());
        $this->assertNull($persons[1]->getLocalDataValue('title'));

        $persons = $this->personProvider->getPersons(2, 2);
        $this->assertCount(1, $persons);
        $this->assertSame('fb', $persons[0]->getIdentifier());
        $this->assertSame('Foo', $persons[0]->getGivenName());
        $this->assertSame('Bar', $persons[0]->getFamilyName());
        $this->assertNull($persons[0]->getLocalDataValue('title'));

        $options = [];
        $persons = $this->personProvider->getPersons(1, 2,
            Options::requestLocalDataAttributes($options, ['title']));
        $this->assertCount(2, $persons);
        $this->assertSame('jm', $persons[0]->getIdentifier());
        $this->assertSame('Jane', $persons[0]->getGivenName());
        $this->assertSame('Moe', $persons[0]->getFamilyName());
        $this->assertSame('Queen', $persons[0]->getLocalDataValue('title'));
        $this->assertSame('jd', $persons[1]->getIdentifier());
        $this->assertSame('John', $persons[1]->getGivenName());
        $this->assertSame('Doe', $persons[1]->getFamilyName());
        $this->assertSame('Dr.', $persons[1]->getLocalDataValue('title'));

        $persons = $this->personProvider->getPersons(2, 2,
            Options::requestLocalDataAttributes($options, ['title']));
        $this->assertCount(1, $persons);
        $this->assertSame('fb', $persons[0]->getIdentifier());
        $this->assertSame('Foo', $persons[0]->getGivenName());
        $this->assertSame('Bar', $persons[0]->getFamilyName());
        $this->assertSame('Lord', $persons[0]->getLocalDataValue('title'));
    }
}
