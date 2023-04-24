<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;

trait PersonTrait
{
    /**
     * @ApiProperty(identifier=true)
     * @Groups({"BasePerson:output"})
     *
     * @var string
     */
    private $identifier;

    /**
     * @ApiProperty(iri="http://schema.org/givenName")
     * @Groups({"BasePerson:output"})
     *
     * @var string
     */
    private $givenName;

    /**
     * @ApiProperty(iri="http://schema.org/familyName")
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
