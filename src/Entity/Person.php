<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Dbp\Relay\CoreBundle\LocalData\LocalDataAwareInterface;
use Dbp\Relay\CoreBundle\LocalData\LocalDataAwareTrait;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get" = {
 *             "path" = "/base/people",
 *             "openapi_context" = {
 *                 "tags" = {"BasePerson"},
 *                 "parameters" = {
 *                     {"name" = "includeLocal", "in" = "query", "description" = "Local data attributes to include", "type" = "string"},
 *                     {"name" = "queryLocal", "in" = "query", "description" = "Local query parameters to apply", "type" = "string"},
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
 *                     {"name" = "identifier", "in" = "path", "description" = "Resource identifier", "required" = true, "type" = "string", "example" = "woody007"},
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
class Person implements PersonInterface, LocalDataAwareInterface
{
    use LocalDataAwareTrait;
    use PersonTrait;

    public const SEARCH_PARAMETER_NAME = 'search';
}
