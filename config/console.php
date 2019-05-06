<?php

return [
    'app' => [
        'modules' => [
            'rbac' => [
                'controllerNamespace' => 'Mailery\Rbac\Commands',
                'controllerMap' => [
                    'migrate' => [
                        '__class' => \Mailery\Rbac\Commands\MigrateController::class,
                        'migrationPath' => [
                            '@app/rbac/migrations',
                        ],
                    ],
                ],
            ],
        ],
        'controllerMap' => [
            'migrate' => [
                'migrationPath' => [
                    '@Yiisoft/Rbac/migrations',
                ],
            ],
        ],
    ],
];
