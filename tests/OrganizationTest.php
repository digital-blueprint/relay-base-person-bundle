<?php

declare(strict_types=1);

namespace Dbp\Relay\BaseBundle\Tests;

use Dbp\Relay\BaseBundle\Entity\Organization;
use PHPUnit\Framework\TestCase;

class OrganizationTest extends TestCase
{
    public function testBasics()
    {
        $org = new Organization();
        $org->setIdentifier('id');
        $this->assertSame('id', $org->getIdentifier());
        $org->setName('name');
        $this->assertSame('name', $org->getName());
        $org->setAlternateName('altname');
        $this->assertSame('altname', $org->getAlternateName());
        $org->setUrl('url');
        $this->assertSame('url', $org->getUrl());
    }
}
