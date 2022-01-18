<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get" = {
 *             "path" = "/base/people",
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
 *             "path" = "/base/people/{identifier}",
 *             "openapi_context" = {
 *                 "tags" = {"BasePerson"},
 *             }
 *
 *         },
 *     },
 *     iri="http://schema.org/Person",
 *     shortName="BasePerson",
 *     description="A person of the LDAP system",
 *     normalizationContext={
 *         "groups" = {"BasePerson:output"},
 *         "jsonld_embed_context" = true,
 *     }
 * )
 */
class Person implements PersonInterface
{
    use PersonTrait;
}
