<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Tests;

use Dbp\Relay\BasePersonBundle\Entity\Person;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
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
    }
}
