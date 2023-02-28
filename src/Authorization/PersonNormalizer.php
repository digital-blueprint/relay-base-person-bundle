<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Authorization;

use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\CoreBundle\Authorization\AuthorizationConfigDefinition;
use Dbp\Relay\CoreBundle\Authorization\Serializer\AbstractEntityDeNormalizer;

class PersonNormalizer extends AbstractEntityDeNormalizer
{
    public function __construct()
    {
        $isCurrentlyLoggedInUserExpression = 'user.getIdentifier() == entity.getIdentifier()';

        $this->configureEntities([
            'BasePerson' => [
                AuthorizationConfigDefinition::ENTITY_CLASS_NAME_CONFIG_NODE => Person::class,
                AuthorizationConfigDefinition::ENTITY_READ_ACCESS_CONFIG_NODE => [
                    'email' => $isCurrentlyLoggedInUserExpression,
                    'birthDate' => $isCurrentlyLoggedInUserExpression,
                ],
            ],
        ]);
    }
}
