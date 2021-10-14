<?php

declare(strict_types=1);

namespace Dbp\Relay\BaseBundle\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;

trait OrganizationTrait
{
    /**
     * @Groups({"BaseOrganization:output"})
     * @ApiProperty(identifier=true)
     *
     * @var string
     */
    private $identifier;

    /**
     * @Groups({"BaseOrganization:output"})
     * @ApiProperty(iri="https://schema.org/name")
     *
     * @var string
     */
    private $name;

    /**
     * @Groups({"BaseOrganization:output"})
     * @ApiProperty(iri="https://schema.org/url")
     *
     * @var string
     */
    private $url;

    /**
     * @Groups({"BaseOrganization:output"})
     * @ApiProperty(iri="https://schema.org/alternateName")
     *
     * @var string
     */
    private $alternateName;

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAlternateName(): ?string
    {
        return $this->alternateName;
    }

    public function setAlternateName(string $alternateName): self
    {
        $this->alternateName = $alternateName;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
