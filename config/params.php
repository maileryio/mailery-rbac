<?php

declare(strict_types=1);

/**
 * Rbac module for Mailery Platform
 * @link      https://github.com/maileryio/mailery-rbac
 * @package   Mailery\Rbac
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

use Yiisoft\Router\UrlGeneratorInterface;

return [
    'maileryio/mailery-menu-navbar' => [
        'items' => [
            'system' => [
                'items' => [
                    'rbac' => [
                        'label' => static function () {
                            return 'Access Control';
                        },
                        'order' => 200,
                        'items' => [
                            'roles' => [
                                'label' => static function () {
                                    return 'Roles';
                                },
                                'url' => static function (UrlGeneratorInterface $urlGenerator) {
                                    return $urlGenerator->generate('/rbac/role/index');
                                },
                                'order' => 100,
                            ],
                            'rules' => [
                                'label' => static function () {
                                    return 'Rules';
                                },
                                'url' => static function (UrlGeneratorInterface $urlGenerator) {
                                    return $urlGenerator->generate('/rbac/rule/index');
                                },
                                'order' => 200,
                            ],
                            'permissions' => [
                                'label' => static function () {
                                    return 'Permissions';
                                },
                                'url' => static function (UrlGeneratorInterface $urlGenerator) {
                                    return $urlGenerator->generate('/rbac/permission/index');
                                },
                                'order' => 300,
                            ],
                        ],
                    ],
                ],
            ]
        ],
    ],
];
