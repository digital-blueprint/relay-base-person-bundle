<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Event;

use Dbp\Relay\CoreBundle\LocalData\LocalDataPostEvent;

class PersonProviderPostEvent extends LocalDataPostEvent
{
    public const NAME = 'dbp.relay.base_person_bundle.person_provider.post';
}
