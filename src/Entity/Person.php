<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Dbp\Relay\CoreBundle\LocalData\LocalDataAwareInterface;
use Dbp\Relay\CoreBundle\LocalData\LocalDataAwareTrait;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get" = {
 *             "path" = "/base/people",
 *             "openapi_context" = {
 *                 "tags" = {"BasePerson"},
 *                 "parameters" = {
 *                     {"name" = "includeLocal", "in" = "query", "description" = "Local data attributes to include", "type" = "string"},
 *                     {"name" = "queryLocal", "in" = "query", "description" = "Local query parameters to apply", "type" = "string"}
 *                 }
 *             }
 *         },
 *     },
 *     itemOperations={
 *         "get" = {
 *             "path" = "/base/people/{identifier}",
 *             "openapi_context" = {
 *                 "tags" = {"BasePerson"},
 *                 "parameters" = {
 *                     {"name" = "identifier", "in" = "path", "description" = "Resource identifier", "required" = true, "type" = "string", "example" = "811EC3ACC0ADCA70"},
 *                     {"name" = "includeLocal", "in" = "query", "description" = "Local data attributes to include", "type" = "string"}
 *                 }
 *             }
 *
 *         },
 *     },
 *     iri="http://schema.org/Person",
 *     shortName="BasePerson",
 *     description="A person of the LDAP system",
 *     normalizationContext={
 *         "groups" = {"BasePerson:output", "LocalData:output"},
 *         "jsonld_embed_context" = true,
 *     }
 * )
 * @ApiFilter(CustomFilter::class, arguments={
 *     "description" = {
 *         "search" = {
 *             "property" = null,
 *             "required" = false,
 *             "description" = "Search filter (whitespace separated list of search terms to perform a partial, case-insensitive text search on person's full name with)",
 *             "schema" = {
 *                 "type" = "string",
 *                 "example" = "Max Mustermann",
 *             },
 *         },
 *     }
 * })
 */
class Person implements LocalDataAwareInterface
{
    use LocalDataAwareTrait;

    public const SEARCH_PARAMETER_NAME = 'search';

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
