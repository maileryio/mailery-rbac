<?php

return [
    'app' => [
        'as access' => [
            '__class' => \Mailery\Rbac\Filters\AccessControl::class,
        ],
    ],
    'menuManager' => [
        'menus' => [
            'sidebar-menu' => [
                'items' => [
                    'system' => [
                        'items' => [
                            'rbac' => [
                                'label' => function () {
                                    return \yii\helpers\Yii::t('app.rbac', 'Permissions');
                                },
                                'url' => ['/rbac/default/index'],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
