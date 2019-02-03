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
    'authManager' => [
        '__class' => \yii\rbac\DbManager::class,
        'itemTable' => '{{%auth_items}}',
        'itemChildTable' => '{{%auth_item_childs}}',
        'assignmentTable' => '{{%auth_assignments}}',
        'ruleTable' => '{{%auth_rules}}',
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
];
