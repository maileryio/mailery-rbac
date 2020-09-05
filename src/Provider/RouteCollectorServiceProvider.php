<?php

namespace Mailery\Rbac\Provider;

use Yiisoft\Di\Container;
use Yiisoft\Di\Support\ServiceProvider;
use Yiisoft\Router\RouteCollectorInterface;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Mailery\Rbac\Controller\PermissionController;
use Mailery\Rbac\Controller\RoleController;
use Mailery\Rbac\Controller\RuleController;
use Mailery\Rbac\Controller\AssignController;
use Mailery\Rbac\Middleware\AssetBundleMiddleware;

final class RouteCollectorServiceProvider extends ServiceProvider
{
    public function register(Container $container): void
    {
        /** @var RouteCollectorInterface $collector */
        $collector = $container->get(RouteCollectorInterface::class);

        $collector->addGroup(
            Group::create(
                '/rbac',
                [
                    // Permissions:
                    Route::get('/permission/index', [PermissionController::class, 'index'])
                        ->name('/rbac/permission/index'),


                    Route::get('/permission/view/{name:\w+}', [PermissionController::class, 'view'])
                        ->name('/rbac/permission/view'),
                    Route::methods(['GET', 'POST'], '/permission/edit/{name:\w+}', [PermissionController::class, 'edit'])
                        ->name('/rbac/permission/edit'),
                    Route::delete('/permission/delete/{name:\w+}', [PermissionController::class, 'delete'])
                        ->name('/rbac/permission/delete'),
                    Route::methods(['GET', 'POST'], '/permission/create', [PermissionController::class, 'create'])
                        ->name('/rbac/permission/create'),

                    // Roles:
                    Route::get('/role/index', [RoleController::class, 'index'])
                        ->name('/rbac/role/index'),
                    Route::get('/role/view/{name:\w+}', [RoleController::class, 'view'])
                        ->name('/rbac/role/view'),
                    Route::methods(['GET', 'POST'], '/role/edit/{name:\w+}', [RoleController::class, 'edit'])
                        ->name('/rbac/role/edit'),
                    Route::delete('/role/delete/{name:\w+}', [RoleController::class, 'delete'])
                        ->name('/rbac/role/delete'),
                    Route::methods(['GET', 'POST'], '/role/create', [RoleController::class, 'create'])
                        ->name('/rbac/role/create'),

                    // Rules:
                    Route::get('/rule/index', [RuleController::class, 'index'])
                        ->name('/rbac/rule/index'),
                    Route::get('/rule/view/{name:\w+}', [RuleController::class, 'view'])
                        ->name('/rbac/rule/view'),
                    Route::methods(['GET', 'POST'], '/rule/edit/{name:\w+}', [RuleController::class, 'edit'])
                         ->name('/rbac/rule/edit'),
                    Route::delete('/rule/delete/{name:\w+}', [RuleController::class, 'delete'])
                        ->name('/rbac/rule/delete'),
                    Route::methods(['GET', 'POST'], '/rule/create', [RuleController::class, 'create'])
                        ->name('/rbac/rule/create'),

                    Route::get('/rule/suggestions', [RuleController::class, 'suggestions'])
                        ->name('/rbac/rule/suggestions'),

                    // Assign:
                    Route::post('/assign', [AssignController::class, 'assign'])
                        ->name('/rbac/assign'),
                    Route::post('/unassign', [AssignController::class, 'unassign'])
                        ->name('/rbac/unassign'),
                    Route::get('/assigned', [AssignController::class, 'assigned'])
                        ->name('/rbac/assigned'),
                    Route::get('/unassigned', [AssignController::class, 'unassigned'])
                        ->name('/rbac/unassigned'),
                ]
            )->addMiddleware(AssetBundleMiddleware::class)
        );
    }
}
