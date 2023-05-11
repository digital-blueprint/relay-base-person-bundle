<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Dbp\Relay\BasePersonBundle\Entity\Person;

class PersonDataProvider implements ProviderInterface
{
    public function provide(Operation $operation, array $uriVariables = [], array $context = [])
    {
        return new Person();
    }
}
