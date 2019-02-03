<?php

return [
    'app' => [
        'modules' => [
            'rbac' => [
                '__class' => \mailery\rbac\Module::class,
                'layout' => 'simple',
                'layoutPath' => '@app/views/layouts',
            ],
        ],
    ],
    'translator' => [
        'translations' => [
            'app.rbac' => [
                '__class' => yii\i18n\PhpMessageSource::class,
                'basePath' => '@mailery/messages',
                'fileMap' => [
                    'app.rbac' => 'messages.php',
                ],
            ],
        ],
    ],
    'authManager' => [
        '__class' => \mailery\rbac\components\DbManager::class,
        'itemTable' => '{{%auth_items}}',
        'itemChildTable' => '{{%auth_item_childs}}',
        'assignmentTable' => '{{%auth_assignments}}',
        'ruleTable' => '{{%auth_rules}}',
    ],
    \yii\rbac\BaseManager::class => \yii\di\Reference::to('authManager'),
    \yii\rbac\ManagerInterface::class => \yii\di\Reference::to('authManager'),
    \mailery\rbac\ManagerInterface::class => \yii\di\Reference::to('authManager'),
];
