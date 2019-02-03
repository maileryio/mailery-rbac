<?php

return [
    'as access' => [
        'class' => \notty\rbac\filters\AccessControl::class,
    ],
    'components' => [
        'menuManager' => [
            'menus' => [
                'sidebar-menu' => [
                    'items' => [
                        'system' => [
                            'items' => [
                                'rbac' => [
                                    'label' => function () {
                                        return \Yii::t('notty.rbac', 'Permissions');
                                    },
                                    'url' => ['/rbac/default/index'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
