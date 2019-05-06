<?php

return [
    'app' => [
        'modules' => [
            'rbac' => [
                '__class' => \Mailery\Rbac\Module::class,
                'layout' => 'simple',
                'layoutPath' => '@app/views/layouts',
            ],
        ],
    ],
    'translator' => [
        'translations' => [
            'app.rbac' => [
                '__class' => \Yiisoft\I18n\Resource\PhpFile::class,
                'basePath' => '@Mailery/Rbac/messages',
                'fileMap' => [
                    'app.rbac' => 'messages.php',
                ],
            ],
        ],
    ],
    'authManager' => [
        '__class' => \Mailery\Rbac\DbManager::class,
        'itemTable' => '{{%auth_items}}',
        'itemChildTable' => '{{%auth_item_childs}}',
        'assignmentTable' => '{{%auth_assignments}}',
        'ruleTable' => '{{%auth_rules}}',
    ],
    \Yiisoft\Rbac\ManagerInterface::class => \yii\di\Reference::to('authManager'),
    \Mailery\Rbac\ManagerInterface::class => \yii\di\Reference::to('authManager'),
    \Yiisoft\Rbac\CheckAccessInterface::class => \yii\di\Reference::to('authManager'),
];
