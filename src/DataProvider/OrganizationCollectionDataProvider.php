<?php

declare(strict_types=1);

namespace Dbp\Relay\BaseBundle\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Dbp\Relay\BaseBundle\API\OrganizationProviderInterface;
use Dbp\Relay\BaseBundle\Entity\Organization;
use Dbp\Relay\CoreBundle\Helpers\ArrayFullPaginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class OrganizationCollectionDataProvider extends AbstractController implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public const ITEMS_PER_PAGE = 250;
    private $api;

    public function __construct(OrganizationProviderInterface $api)
    {
        $this->api = $api;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Organization::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): ArrayFullPaginator
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $filters = $context['filters'] ?? [];
        $lang = $filters['lang'] ?? 'de';

        $perPage = self::ITEMS_PER_PAGE;
        $page = 1;

        if (isset($context['filters']['page'])) {
            $page = (int) $context['filters']['page'];
        }

        if (isset($context['filters']['perPage'])) {
            $perPage = (int) $context['filters']['perPage'];
        }
        $orgs = $this->api->getOrganizations($lang);

        return new ArrayFullPaginator($orgs, $page, $perPage);
    }
}
