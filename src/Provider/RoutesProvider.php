<?php

namespace Mailery\Rbac\Provider;

use Mailery\Rbac\Controller\RoleController;
use Mailery\Rbac\Controller\RuleController;
use Mailery\Rbac\Controller\PermissionController;
use Mailery\Web\Provider\RoutesProviderInterface;
use Psr\Container\ContainerInterface;
use Yiisoft\Router\Route;
use Yiisoft\Yii\Web\Middleware\ActionCaller;

class RoutesProvider implements RoutesProviderInterface
{

    /**
     * @inheritdoc
     */
    public function getRoutes(ContainerInterface $container): array
    {
        return [
            // permissions
            Route::get('/rbac/permission/index', new ActionCaller(PermissionController::class, 'index', $container))
                ->name('/rbac/permission/index'),
            Route::get('/rbac/permission/view/{name:\w+}')
                ->name('/rbac/permission/view', new ActionCaller(PermissionController::class, 'view', $container)),
            Route::methods(['GET', 'POST'], '/rbac/permission/edit/{name:\w+}')
                ->name('/rbac/permission/edit', new ActionCaller(PermissionController::class, 'edit', $container)),
            Route::delete('/rbac/permission/delete/{name:\w+}', new ActionCaller(PermissionController::class, 'delete', $container))
                ->name('/rbac/permission/delete'),
            Route::methods(['GET', 'POST'], '/rbac/permission/create', new ActionCaller(PermissionController::class, 'create', $container))
                ->name('/rbac/permission/create'),

            // roles
            Route::get('/rbac/role/index', new ActionCaller(RoleController::class, 'index', $container))
                ->name('/rbac/role/index'),
            Route::get('/rbac/role/view/{name:\w+}', new ActionCaller(RoleController::class, 'view', $container))
                ->name('/rbac/role/view'),
            Route::methods(['GET', 'POST'], '/rbac/role/edit/{name:\w+}', new ActionCaller(RoleController::class, 'edit', $container))
                ->name('/rbac/role/edit'),
            Route::delete('/rbac/role/delete/{name:\w+}', new ActionCaller(RoleController::class, 'delete', $container))
                ->name('/rbac/role/delete'),
            Route::methods(['GET', 'POST'], '/rbac/role/create', new ActionCaller(RoleController::class, 'create', $container))
                ->name('/rbac/role/create'),

            // rules
            Route::get('/rbac/rule/index', new ActionCaller(RuleController::class, 'index', $container))
                ->name('/rbac/rule/index'),
            Route::get('/rbac/rule/view/{name:\w+}', new ActionCaller(RuleController::class, 'view', $container))
                ->name('/rbac/rule/view'),
            Route::methods(['GET', 'POST'], '/rbac/rule/edit/{name:\w+}', new ActionCaller(RuleController::class, 'edit', $container)),
            Route::delete('/rbac/rule/delete/{name:\w+}', new ActionCaller(RuleController::class, 'delete', $container))
                ->name('/rbac/rule/delete'),
            Route::methods(['GET', 'POST'], '/rbac/rule/create', new ActionCaller(RuleController::class, 'create', $container))
                ->name('/rbac/rule/create'),
        ];
    }

}
