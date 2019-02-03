<?php

return [
    'app' => [
        'modules' => [
            'rbac' => [
                'controllerNamespace' => 'mailery\rbac\commands',
                'controllerMap' => [
                    'migrate' => [
                        '__class' => \mailery\rbac\commands\MigrateController::class,
                        'migrationPath' => [
                            '@app/rbac/migrations',
                            '@mailery/rbac/migrations',
                        ],
                    ],
                ],
            ],
        ],
        'controllerMap' => [
            'migrate' => [
                'migrationPath' => [
                    '@yii/rbac/migrations',
                ],
            ],
        ],
    ],
];
