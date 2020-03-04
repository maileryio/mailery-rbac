<?php

use Mailery\Rbac\Manager\ManagerFactory;
use Yiisoft\Rbac\ManagerInterface;
use Yiisoft\Access\AccessCheckerInterface;
use Yiisoft\Factory\Definitions\Reference;

return [
    ManagerInterface::class => new ManagerFactory($params['rbac']),
    AccessCheckerInterface::class => Reference::to(ManagerInterface::class),

//    RoutesProviderFactory::class => [
//        '__construct()' => [
//            RoutesProvider::class,
//        ],
//    ],
//    SidebarMenu::class => [
//        'setItems()' => [
//            'items' => [
//                'rbac' => [
//                    'label' => function () {
//                        return 'Access Control';
//                    },
//                    'icon' => 'accessibility',
//                    'items' => [
//                        'roles' => [
//                            'label' => function () {
//                                return 'Roles';
//                            },
//                            'url' => function (ContainerInterface $container) {
//                                return $container->get(UrlGeneratorInterface::class)
//                                    ->generate('/rbac/role/index');
//                            },
//                        ],
//                        'rules' => [
//                            'label' => function () {
//                                return 'Rules';
//                            },
//                            'url' => function (ContainerInterface $container) {
//                                return $container->get(UrlGeneratorInterface::class)
//                                    ->generate('/rbac/rule/index');
//                            },
//                        ],
//                        'permissions' => [
//                            'label' => 'Permissions',
//                            'url' => function (ContainerInterface $container) {
//                                return $container->get(UrlGeneratorInterface::class)
//                                    ->generate('/rbac/permission/index');
//                            },
//                        ],
//                    ],
//                ],
//            ],
//        ],
//    ],
];
