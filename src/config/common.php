<?php

return [
    'components' => [
        'i18n' => [
            'translations' => [
                'notty.rbac' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@notty/rbac/messages',
                    'fileMap' => [
                        'notty.rbac' => 'messages.php',
                    ],
                ],
            ],
        ],
        'authManager' => [
            'class' => \yii\rbac\DbManager::class,
            'itemTable' => '{{%auth_items}}',
            'itemChildTable' => '{{%auth_item_childs}}',
            'assignmentTable' => '{{%auth_assignments}}',
            'ruleTable' => '{{%auth_rules}}',
        ],
    ],
    'container' => [
        'definitions' => [
            \yii\rbac\ManagerInterface::class => function () {
                return \Yii::$app->authManager;
            },
        ],
    ],
    'modules' => [
        'rbac' => [
            'class' => \notty\rbac\Module::class,
            'layout' => 'simple',
            'layoutPath' => '@notty/views/layouts',
        ],
    ],
];
