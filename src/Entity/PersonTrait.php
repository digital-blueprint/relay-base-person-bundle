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
     * @var string
     * @ApiProperty(iri="http://schema.org/familyName")
     * @Groups({"BasePerson:output"})
     *
     * @var string
     */
    private $familyName;

    /**
     * @ApiProperty(iri="http://schema.org/email")
     * @Groups({"BasePerson:output:email"})
     *
     * @var string
     */
    private $email;

    /**
     * @var string
     * @ApiProperty(iri="http://schema.org/birthDate")
     * @Groups({"BasePerson:output:birthDate"})
     */
    private $birthDate;

    /**
     * @var array
     */
    private $extraData;

    public function __construct()
    {
        $this->extraData = [];
    }

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Allows attaching extra information to a Person object with
     * some random key. You can get the value back via getExtraData().
     *
     * @param ?mixed $value
     */
    public function setExtraData(string $key, $value): void
    {
        $this->extraData[$key] = $value;
    }

    /**
     * @return ?mixed
     */
    public function getExtraData(string $key)
    {
        return $this->extraData[$key] ?? null;
    }

    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    public function setBirthDate(string $birthDate): void
    {
        $this->birthDate = $birthDate;
    }
}
