<?php

return [
    'app' => [
        'as access' => [
            '__class' => \mailery\rbac\filters\AccessControl::class,
        ],
    ],
//    'components' => [
//        'menuManager' => [
//            'menus' => [
//                'sidebar-menu' => [
//                    'items' => [
//                        'system' => [
//                            'items' => [
//                                'rbac' => [
//                                    'label' => function () {
//                                        return \Yii::t('mailery.rbac', 'Permissions');
//                                    },
//                                    'url' => ['/rbac/default/index'],
//                                ],
//                            ],
//                        ],
//                    ],
//                ],
//            ],
//        ],
//    ],
];
