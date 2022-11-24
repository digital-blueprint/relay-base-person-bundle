<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Event;

use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\CoreBundle\LocalData\LocalDataAwarePostEvent;

class PersonProviderPostEvent extends LocalDataAwarePostEvent
{
    public const NAME = 'dbp.relay.base_person_bundle.person_provider.post';

    /** @var array */
    private $sourceData;

    /** @var Person */
    private $person;

    public function __construct(array $sourceData, Person $person)
    {
        parent::__construct($person);

        $this->sourceData = $sourceData;
        $this->person = $person;
    }

    public function getEntity(): Person
    {
        return $this->person;
    }

    public function getSourceData(): array
    {
        return $this->sourceData;
    }
}
