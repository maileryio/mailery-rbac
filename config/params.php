<?php

declare(strict_types=1);

/**
 * Rbac module for Mailery Platform
 * @link      https://github.com/maileryio/mailery-rbac
 * @package   Mailery\Rbac
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

use Mailery\Menu\MenuItem;
use Mailery\Rbac\Controller\AssignController;
use Mailery\Rbac\Controller\PermissionController;
use Mailery\Rbac\Controller\RoleController;
use Mailery\Rbac\Controller\RuleController;
use Mailery\Rbac\Middleware\AssetBundleMiddleware;
use Opis\Closure\SerializableClosure;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Yiisoft\Router\UrlGeneratorInterface;

return [
    'rbacNavbarMenuItem' => (new MenuItem())
            ->withLabel('Access Control')
            ->withChildItems([
                'roles' => (new MenuItem())
                    ->withLabel('Roles')
                    ->withUrl(new SerializableClosure(function (UrlGeneratorInterface $urlGenerator) {
                        return $urlGenerator->generate('/rbac/role/index');
                    }))
                    ->withOrder(100),
                'rules' => (new MenuItem())
                    ->withLabel('Rules')
                    ->withUrl(new SerializableClosure(function (UrlGeneratorInterface $urlGenerator) {
                        return $urlGenerator->generate('/rbac/rule/index');
                    }))
                    ->withOrder(200),
                'permissions' => (new MenuItem())
                    ->withLabel('Permissions')
                    ->withUrl(new SerializableClosure(function (UrlGeneratorInterface $urlGenerator) {
                        return $urlGenerator->generate('/rbac/permission/index');
                    }))
                    ->withOrder(300),
            ])
            ->withOrder(200),

    'rbac' => [
        'directory' => '@root/rbac',
        'defaultRoles' => ['admin', 'guest'],
    ],
];
