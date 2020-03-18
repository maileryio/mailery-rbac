<?php

use Mailery\Menu\MenuItem;
use Mailery\Rbac\Controller\AssignController;
use Mailery\Rbac\Controller\RoleController;
use Mailery\Rbac\Controller\RuleController;
use Mailery\Rbac\Controller\PermissionController;
use Yiisoft\Router\Route;
use Yiisoft\Router\UrlGeneratorInterface;
use Opis\Closure\SerializableClosure;
use Mailery\Web\Assets\AppAssetBundle;
use Mailery\Rbac\Assets\RbacAssetBundle;

return [
    'rbacNavbarMenuItem' => (new MenuItem())
            ->withLabel('Access Control')
            ->withChildItems([
                'roles' => (new MenuItem())
                    ->withLabel('Roles')
                    ->withUrl(new SerializableClosure(function (UrlGeneratorInterface $urlGenerator) {
                        return $urlGenerator->generate('/rbac/role/index');
                    })),
                'rules' => (new MenuItem())
                    ->withLabel('Rules')
                    ->withUrl(new SerializableClosure(function (UrlGeneratorInterface $urlGenerator) {
                        return $urlGenerator->generate('/rbac/rule/index');
                    })),
                'permissions' => (new MenuItem())
                    ->withLabel('Permissions')
                    ->withUrl(new SerializableClosure(function (UrlGeneratorInterface $urlGenerator) {
                        return $urlGenerator->generate('/rbac/permission/index');
                    })),
            ]),

    'assetManager' => [
        'bundles' => [
            AppAssetBundle::class => [
                'depends' => [
                    RbacAssetBundle::class,
                ],
            ],
        ],
    ],

    'rbac' => [
        'directory' => '@root/rbac',
        'defaultRoles' => ['admin', 'guest'],
    ],

    'router' => [
        'routes' => [
            // permissions
            '/rbac/permission/index' => Route::get('/rbac/permission/index', [PermissionController::class, 'index'])
                ->name('/rbac/permission/index'),
            '/rbac/permission/view' => Route::get('/rbac/permission/view/{name:\w+}', [PermissionController::class, 'view'])
                ->name('/rbac/permission/view'),
            '/rbac/permission/edit' => Route::methods(['GET', 'POST'], '/rbac/permission/edit/{name:\w+}', [PermissionController::class, 'edit'])
                ->name('/rbac/permission/edit'),
            '/rbac/permission/delete' => Route::delete('/rbac/permission/delete/{name:\w+}', [PermissionController::class, 'delete'])
                ->name('/rbac/permission/delete'),
            '/rbac/permission/create' => Route::methods(['GET', 'POST'], '/rbac/permission/create', [PermissionController::class, 'create'])
                ->name('/rbac/permission/create'),

            // roles
            '/rbac/role/index' => Route::get('/rbac/role/index', [RoleController::class, 'index'])
                ->name('/rbac/role/index'),
            '/rbac/role/view' => Route::get('/rbac/role/view/{name:\w+}', [RoleController::class, 'view'])
                ->name('/rbac/role/view'),
            '/rbac/role/edit' => Route::methods(['GET', 'POST'], '/rbac/role/edit/{name:\w+}', [RoleController::class, 'edit'])
                ->name('/rbac/role/edit'),
            '/rbac/role/delete' => Route::delete('/rbac/role/delete/{name:\w+}', [RoleController::class, 'delete'])
                ->name('/rbac/role/delete'),
            '/rbac/role/create' => Route::methods(['GET', 'POST'], '/rbac/role/create', [RoleController::class, 'create'])
                ->name('/rbac/role/create'),
            '/rbac/role/assign' => Route::post('/rbac/role/assign', [AssignController::class, 'assign'])
                ->name('/rbac/role/assign'),
            '/rbac/role/unassign' => Route::post('/rbac/role/unassign', [AssignController::class, 'unassign'])
                ->name('/rbac/role/unassign'),
            '/rbac/role/assigned' => Route::get('/rbac/role/assigned', [AssignController::class, 'assigned'])
                ->name('/rbac/role/assigned'),
            '/rbac/role/unassigned' => Route::get('/rbac/role/unassigned', [AssignController::class, 'unassigned'])
                ->name('/rbac/role/unassigned'),

            // rules
            '/rbac/rule/index' => Route::get('/rbac/rule/index', [RuleController::class, 'index'])
                ->name('/rbac/rule/index'),
            '/rbac/rule/view' => Route::get('/rbac/rule/view/{name:\w+}', [RuleController::class, 'view'])
                ->name('/rbac/rule/view'),
            '/rbac/rule/edit' => Route::methods(['GET', 'POST'], '/rbac/rule/edit/{name:\w+}', [RuleController::class, 'edit'])
                 ->name('/rbac/rule/edit'),
            '/rbac/rule/delete' => Route::delete('/rbac/rule/delete/{name:\w+}', [RuleController::class, 'delete'])
                ->name('/rbac/rule/delete'),
            '/rbac/rule/create' => Route::methods(['GET', 'POST'], '/rbac/rule/create', [RuleController::class, 'create'])
                ->name('/rbac/rule/create'),
        ],
    ],
];
