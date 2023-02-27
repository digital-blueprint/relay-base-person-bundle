<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Authorization;

use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\CoreBundle\Authorization\AbstractAuthorizationService;
use Dbp\Relay\CoreBundle\Authorization\AuthorizationConfigDefinition;

class AuthorizationService extends AbstractAuthorizationService
{
    public function __construct()
    {
        $isLoggedInUserExpression = 'user.getIdentifier() == entity.getIdentifier()';

        $this->configure([], [], [
            'BasePerson' => [
                AuthorizationConfigDefinition::ENTITY_CLASS_NAME_CONFIG_NODE => Person::class,
                AuthorizationConfigDefinition::ENTITY_READ_ACCESS_CONFIG_NODE => [
                    'email' => $isLoggedInUserExpression,
                    'birthDate' => $isLoggedInUserExpression,
                ],
            ],
        ]);
    }
}
