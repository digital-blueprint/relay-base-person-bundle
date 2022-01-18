<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Tests;

use Dbp\Relay\BasePersonBundle\Entity\Person;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    public function testExtraData()
    {
        $person = new Person();

        $person->setExtraData('foo', 42);
        $this->assertSame(42, $person->getExtraData('foo'));

        $person->setExtraData('foo', [1]);
        $this->assertSame([1], $person->getExtraData('foo'));

        $this->assertSame(null, $person->getExtraData('nope'));
    }

    public function testGettersSetters()
    {
        $person = new Person();

        $this->assertNull($person->getIdentifier());
        $person->setIdentifier('foo');
        $this->assertSame('foo', $person->getIdentifier());

        $this->assertNull($person->getGivenName());
        $person->setGivenName('foo');
        $this->assertSame('foo', $person->getGivenName());

        $this->assertNull($person->getFamilyName());
        $person->setFamilyName('foo');
        $this->assertSame('foo', $person->getFamilyName());

        $this->assertNull($person->getEmail());
        $person->setEmail('foo@invalid.com');
        $this->assertSame('foo@invalid.com', $person->getEmail());

        $this->assertNull($person->getBirthDate());
        $person->setBirthDate('1970-01-01');
        $this->assertSame('1970-01-01', $person->getBirthDate());
    }
}
