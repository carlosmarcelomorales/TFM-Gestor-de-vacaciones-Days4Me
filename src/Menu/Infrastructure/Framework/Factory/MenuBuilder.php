<?php
declare(strict_types=1);


namespace TFM\HolidaysManagement\Menu\Infrastructure\Framework\Factory;


use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class MenuBuilder
{
    private FactoryInterface $factory;
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->factory = $factory;
        $this->authorizationChecker = $authorizationChecker;

    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root',
            [
                'childrenAttributes' => [
                    'class' => 'navbar-nav',
                ],
            ]
        );
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {

            if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN') ||
                $this->authorizationChecker->isGranted('ROLE_COMPANY_ADMIN') ||
                $this->authorizationChecker->isGranted('ROLE_COMPANY_HEAD') ||
                $this->authorizationChecker->isGranted('ROLE_USER')) {

                $menu->addChild(
                    'Dashboard',
                    [
                        'route' => 'dashboard',
                    ])->setAttribute('Class', 'nav-item')->setLinkAttribute('class', 'nav-link');

                $menu->addChild(
                    'Calendar',
                    [

                        'route' => 'calendar',
                    ])->setAttribute('Class', 'nav-item')->setLinkAttribute('class', 'nav-link');

                $menu->addChild(
                    'Request',
                    [

                        'route' => 'list_request',
                    ])->setAttribute('Class', 'nav-item')->setLinkAttribute('class', 'nav-link');
            }

            if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN') ||
                $this->authorizationChecker->isGranted('ROLE_COMPANY_ADMIN') ||
                $this->authorizationChecker->isGranted('ROLE_COMPANY_HEAD')
            ) {
                $menu->addChild(
                    'Management',
                    [
                        'route' => 'management_request',
                    ]
                )->setAttribute('Class', 'nav-item')->setLinkAttribute('class', 'nav-link');

            }

            if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN') ||
                $this->authorizationChecker->isGranted('ROLE_COMPANY_ADMIN')) {
                $menu->addChild(
                    'Company',
                    [
                       'route' => 'list_company'
                    ]
                    )->setAttribute('Class', 'nav-item')->setLinkAttribute('class', 'nav-link');

                $menu->addChild(
                    'Users',
                    [
                        'route' => 'list_user',
                    ])->setAttribute('Class', 'nav-item')->setLinkAttribute('class', 'nav-link');

                $menu->addChild(
                    'Departments',
                    [
                        'route' => 'list_department',
                    ])->setAttribute('Class', 'nav-item')->setLinkAttribute('class', 'nav-link');

                $menu->addChild(
                    'Holidays',
                    [
                        'route' => 'list_holiday',
                    ])->setAttribute('Class', 'nav-item')->setLinkAttribute('class', 'nav-link');

                $menu->addChild(
                    'WorkPlaces',
                    [
                        'route' => 'list_workPlace',
                    ])->setAttribute('Class', 'nav-item')->setLinkAttribute('class', 'nav-link');

                $menu->addChild(
                    'WorkPositions',
                    [
                        'route' => 'list_workPosition',
                    ])->setAttribute('Class', 'nav-item')->setLinkAttribute('class', 'nav-link');
            }

            if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN') ||
                $this->authorizationChecker->isGranted('ROLE_COMPANY_ADMIN') ||
                $this->authorizationChecker->isGranted('ROLE_COMPANY_HEAD') ||
                $this->authorizationChecker->isGranted('ROLE_USER')) {

                $menu->addChild(
                    'Profile',
                    [
                        'route' => 'profile',
                    ])->setAttribute('Class', 'nav-item')->setLinkAttribute('class', 'nav-link');
            }

            return $menu;
        }
    }
}
