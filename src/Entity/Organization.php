<?php

declare(strict_types=1);

namespace Dbp\Relay\BaseBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Dbp\Relay\BaseBundle\Controller\GetOrganizationsByPerson;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get" = {
 *             "path" = "/organizations",
 *             "openapi_context" = {
 *                 "tags" = {"Base"},
 *                 "parameters" = {
 *                     {"name" = "lang", "in" = "query", "description" = "Language of result", "type" = "string", "enum" = {"de", "en"}, "example" = "de"}
 *                 }
 *             }
 *         },
 *         "get_orgs" = {
 *             "method" = "GET",
 *             "path" = "/people/{identifier}/organizations",
 *             "controller" = GetOrganizationsByPerson::class,
 *             "read" = false,
 *             "openapi_context" = {
 *                 "tags" = {"Base"},
 *                 "summary" = "Get the organizations related to a person.",
 *                 "parameters" = {
 *                     {"name" = "identifier", "in" = "path", "description" = "Id of person", "required" = true, "type" = "string", "example" = "vlts01"},
 *                     {"name" = "context", "in" = "query", "description" = "type of relation", "required" = false, "type" = "string", "example" = "library-manager"},
 *                     {"name" = "lang", "in" = "query", "description" = "language", "type" = "string", "example" = "en"},
 *                 }
 *             },
 *         }
 *     },
 *     itemOperations={
 *         "get" = {
 *             "path" = "/organizations/{identifier}",
 *             "openapi_context" = {
 *                 "tags" = {"Base"},
 *                 "parameters" = {
 *                     {"name" = "identifier", "in" = "path", "description" = "orgUnitID of organization", "required" = true, "type" = "string", "example" = "1190-F2050"},
 *                     {"name" = "lang", "in" = "query", "description" = "Language of result", "type" = "string", "enum" = {"de", "en"}, "example" = "de"}
 *                 }
 *             }
 *         },
 *     },
 *     iri="http://schema.org/Organization",
 *     description="An organization",
 *     normalizationContext={
 *         "jsonld_embed_context" = true,
 *         "groups" = {"Organization:output"}
 *     }
 * )
 */
class Organization
{
    use OrganizationTrait;
}
