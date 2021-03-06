<?php

namespace TwinElements\AdminBundle\Menu;

use App\AdminRoles;
use Knp\Menu\FactoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use TwinElements\AdminBundle\Role\AdminUserRole;

/**
 * @Cache(expires="tomorrow")
 */
class Builder
{
    private $factory;

    private $authChecker;

    /**
     * @var TokenStorageInterface $tokenStorage
     */
    private $tokenStorage;

    /**
     * @var AdminMenuCollector
     */
    private $menuCollector;

    /**
     * @var TranslatorInterface $translator
     */
    private $translator;

    /**
     * @var string|null
     */
    private $adminLocale;

    public function __construct(
        FactoryInterface              $factory,
        AuthorizationCheckerInterface $authChecker,
        TokenStorageInterface         $tokenStorage,
        AdminMenuCollector            $menuCollector,
        TranslatorInterface           $translator,
        string                        $adminLocale
    )
    {
        $this->menuCollector = $menuCollector;
        $this->factory = $factory;
        $this->authChecker = $authChecker;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
        $this->adminLocale = $adminLocale;
    }

    public function mainMenu(array $options)
    {
        /**
         * @var AuthorizationCheckerInterface $security
         */
        $security = $this->authChecker;

        $menu = $this->factory->createItem('root');
        $intermediateMenu = $this->menuCollector->getItems();

        uasort($intermediateMenu, [$this, 'sortMenu']);

        foreach ($intermediateMenu as $menuItem) {

            if ($menuItem->getRole() && (!$security->isGranted(AdminUserRole::ROLE_ADMIN) && !$security->isGranted($menuItem->getRole()))) {
                continue;
            }

            if (is_array($menuItem->getVoter()) && !$security->isGranted($menuItem->getVoter()['0'], $menuItem->getVoter()['1'])) {
                continue;
            }

            $menu->addChild($this->translator->trans($menuItem->getName(), [], null, $this->adminLocale), [
                'route' => $menuItem->getRoute(),
                'routeParameters' => $menuItem->getRouteParams()
            ]);
        }

        return $menu;
    }

    private function sortMenu(MenuItem $current, MenuItem $previous)
    {
        return $current->getPriority() - $previous->getPriority();
    }
}
