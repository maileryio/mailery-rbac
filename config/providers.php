<?php

use Mailery\Rbac\Provider\RbacProvider;

return [
    RbacProvider::class => new RbacProvider($params['rbac']),
];
