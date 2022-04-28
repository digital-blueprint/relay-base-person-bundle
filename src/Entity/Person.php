<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Entity;

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
 *                     {"name" = "search", "in" = "query", "description" = "Search for a person name", "type" = "string", "example" = "woody007"},
 *                     {"name" = "includeLocal", "in" = "query", "description" = "Local data attributes to include", "type" = "string"}
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
 */
class Person implements PersonInterface, LocalDataAwareInterface
{
    use LocalDataAwareTrait;
    use PersonTrait;
}
