<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\DependencyInjection;

use Dbp\Relay\BasePersonBundle\Serializer\PersonAttributeNormalizer;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('dbp_relay_base_person');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode->append(PersonAttributeNormalizer::getAttributeAccessConfigNodeDefinition([
            'BasePerson' => [
                'email',
                'birthDate',
            ],
        ]));

        return $treeBuilder;
    }
}
