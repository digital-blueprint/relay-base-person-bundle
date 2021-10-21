<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get" = {
 *             "path" = "/people",
 *             "openapi_context" = {
 *                 "tags" = {"BasePerson"},
 *                 "parameters" = {
 *                     {"name" = "search", "in" = "query", "description" = "Search for a person name", "type" = "string", "example" = "woody007"}
 *                 }
 *             }
 *         },
 *     },
 *     itemOperations={
 *         "get" = {
 *             "path" = "/people/{identifier}",
 *             "openapi_context" = {
 *                 "tags" = {"BasePerson"},
 *             }
 *
 *         },
 *     },
 *     iri="http://schema.org/Person",
 *     description="A person of the LDAP system",
 *     normalizationContext={
 *         "groups" = {"BasePerson:output"},
 *         "jsonld_embed_context" = true,
 *     }
 * )
 */
class Person
{
    use PersonTrait;
}
