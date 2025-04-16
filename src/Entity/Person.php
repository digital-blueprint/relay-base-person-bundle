<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\Parameter;
use Dbp\Relay\BasePersonBundle\DataProvider\PersonDataProvider;
use Dbp\Relay\CoreBundle\LocalData\LocalDataAwareInterface;
use Dbp\Relay\CoreBundle\LocalData\LocalDataAwareTrait;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    shortName: 'BasePerson',
    types: ['https://schema.org/Person'],
    operations: [
        new Get(
            uriTemplate: '/base/people/{identifier}',
            openapi: new Operation(
                tags: ['BasePerson'],
                parameters: [
                    new Parameter(
                        name: 'includeLocal',
                        in: 'query',
                        description: 'Local data attributes to include',
                        schema: ['type' => 'string']
                    ),
                ]
            ),
            provider: PersonDataProvider::class
        ),
        new GetCollection(
            uriTemplate: '/base/people',
            openapi: new Operation(
                tags: ['BasePerson'],
                parameters: [
                    new Parameter(
                        name: 'search',
                        in: 'query',
                        description: "Search filter (whitespace separated list of search terms to perform a partial, case-insensitive text search on person's full name with)",
                        required: false,
                        schema: ['type' => 'string']
                    ),
                    new Parameter(
                        name: 'includeLocal',
                        in: 'query',
                        description: 'Local data attributes to include',
                        schema: ['type' => 'string']
                    ),
                    new Parameter(
                        name: 'preparedFilter',
                        in: 'query',
                        description: 'Prepared filter to apply',
                        schema: ['type' => 'string']
                    ),
                    new Parameter(
                        name: 'filter',
                        in: 'query',
                        schema: [
                            'type' => 'object',
                            'additionalProperties' => [
                                'type' => 'string',
                            ],
                        ],
                        style: 'form',
                        explode: true
                    ),
                ]
            ),
            provider: PersonDataProvider::class
        ),
    ],
    normalizationContext: [
        'groups' => ['BasePerson:output', 'LocalData:output'],
        'jsonld_embed_context' => true,
    ]
)]
class Person implements LocalDataAwareInterface
{
    use LocalDataAwareTrait;

    public const SEARCH_PARAMETER_NAME = 'search';

    #[ApiProperty(identifier: true)]
    #[Groups(['BasePerson:output'])]
    private ?string $identifier = null;

    #[ApiProperty(iris: ['https://schema.org/givenName'])]
    #[Groups(['BasePerson:output'])]
    private ?string $givenName = null;

    #[ApiProperty(iris: 'https://schema.org/familyName')]
    #[Groups(['BasePerson:output'])]
    private ?string $familyName = null;

    public function setIdentifier(?string $identifier): void
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

    public function setGivenName(?string $givenName): void
    {
        $this->givenName = $givenName;
    }

    public function getFamilyName(): ?string
    {
        return $this->familyName;
    }

    public function setFamilyName(?string $familyName): void
    {
        $this->familyName = $familyName;
    }
}
