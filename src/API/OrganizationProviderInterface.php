<?php

declare(strict_types=1);

namespace DBP\API\BaseBundle\API;

use DBP\API\BaseBundle\Entity\Organization;
use DBP\API\BaseBundle\Entity\Person;

interface OrganizationProviderInterface
{
    public function getOrganizationById(string $identifier, string $lang): Organization;

    /**
     * @return Organization[]
     */
    public function getOrganizationsByPerson(Person $person, string $context, string $lang): array;

    /**
     * @return Organization[]
     */
    public function getOrganizations(string $lang): array;
}
