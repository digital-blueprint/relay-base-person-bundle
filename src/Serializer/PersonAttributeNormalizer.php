<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Serializer;

use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\CoreBundle\Authorization\Serializer\AbstractEntityNormalizer;

class PersonAttributeNormalizer extends AbstractEntityNormalizer
{
    public function __construct()
    {
        parent::__construct([Person::class]);
    }
}
