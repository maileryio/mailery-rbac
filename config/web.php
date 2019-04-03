<?php

return [
    'app' => [
        'as access' => [
            '__class' => \mailery\rbac\filters\AccessControl::class,
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
