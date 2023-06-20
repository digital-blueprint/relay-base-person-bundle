<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Entity;

use Dbp\Relay\CoreBundle\LocalData\LocalDataAwareInterface;
use Dbp\Relay\CoreBundle\LocalData\LocalDataAwareTrait;
use Symfony\Component\Serializer\Annotation\Groups;

class Person implements LocalDataAwareInterface
{
    use LocalDataAwareTrait;

    public const SEARCH_PARAMETER_NAME = 'search';

    /**
     * @Groups({"BasePerson:output"})
     *
     * @var string
     */
    private $identifier;

    /**
     * @Groups({"BasePerson:output"})
     *
     * @var string
     */
    private $givenName;

    /**
     * @Groups({"BasePerson:output"})
     *
     * @var string
     */
    private $familyName;

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function getGivenName(): ?string
    {
        return $this->givenName;
    }

    public function setGivenName(string $givenName): void
    {
        $this->givenName = $givenName;
    }

    public function getFamilyName(): ?string
    {
        return $this->familyName;
    }

    public function setFamilyName(string $familyName): void
    {
        $this->familyName = $familyName;
    }
}
