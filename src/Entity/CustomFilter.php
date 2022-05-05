<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Entity;

use ApiPlatform\Core\Api\FilterInterface;

class CustomFilter implements FilterInterface
{
    private $description;

    public function __construct(array $description)
    {
        $this->description = $description;
    }

    public function getDescription(string $resourceClass): array
    {
        return $this->description;
    }
}
