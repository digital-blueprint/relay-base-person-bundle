<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Dbp\Relay\BasePersonBundle\API\PersonProviderInterface;
use Dbp\Relay\CoreBundle\LocalData\AbstractLocalDataAuthorizationService;
use Dbp\Relay\CoreBundle\LocalData\LocalData;
use Dbp\Relay\CoreBundle\Locale\Locale;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class PersonStateProvider extends AbstractLocalDataAuthorizationService implements ProviderInterface
{
    /**
     * @var PersonProviderInterface
     */
    private $personProvider;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var Locale
     */
    private $locale;

    public function __construct(PersonProviderInterface $personProvider, Security $security, Locale $locale)
    {
        $this->personProvider = $personProvider;
        $this->security = $security;
        $this->locale = $locale;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        if ($operation instanceof CollectionOperationInterface) {
            return [];
        } else {
            $options = [];
            $options[Locale::LANGUAGE_OPTION] = $this->locale->getCurrentPrimaryLanguage();
            $filters = $context['filters'] ?? [];
            LocalData::addOptions($options, $filters);

            dump($context);
            //return $this->personProvider->getPerson($id, $options);
        }

    }
}
