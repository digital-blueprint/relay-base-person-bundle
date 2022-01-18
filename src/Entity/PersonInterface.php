<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Entity;

interface PersonInterface
{
    public function setIdentifier(string $identifier): void;

    public function getIdentifier(): ?string;

    public function getGivenName(): ?string;

    public function setGivenName(string $givenName): void;

    public function getFamilyName(): ?string;

    public function setFamilyName(string $familyName): void;

    public function getEmail(): ?string;

    public function setEmail(string $email): void;

    public function getBirthDate(): ?string;

    public function setBirthDate(string $birthDate): void;
}
