<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class AdminTopRightNavMenuBuilder
{
    public function __construct(
        private FactoryInterface $factory,
        private Security $security,
    ) {
    }

    public function createTopRightUserNavMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('topnav');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        $menu
            ->addChild(
                'username',
                [
                    'label' => sprintf('<i class="far fa-user fa-fw margin-r-5"></i> %s', $this->security->getUser()?->getUserIdentifier()),
                    'uri' => '#',
                ]
            )
            ->setExtras(
                [
                    'safe_label' => true,
                ]
            )
            ->setCurrent(true)
        ;
        $menu
            ->addChild(
                'logout',
                [
                    'label' => '<i class="fas fa-power-off fa-fw"></i>',
                    'route' => 'admin_app_logout',
                ]
            )
            ->setExtras(
                [
                    'safe_label' => true,
                ]
            )
        ;

        return $menu;
    }
}
