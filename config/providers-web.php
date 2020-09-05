<?php

declare(strict_types=1);

use Mailery\Rbac\Provider\RbacProvider;
use Mailery\Rbac\Provider\RouteCollectorServiceProvider;

return [
    RbacProvider::class => new RbacProvider($params['rbac']),
    RouteCollectorServiceProvider::class => RouteCollectorServiceProvider::class,
];
