<?php

use Mailery\Rbac\Manager\ManagerFactory;
use Yiisoft\Rbac\ManagerInterface;
use Yiisoft\Access\AccessCheckerInterface;
use Yiisoft\Factory\Definitions\Reference;

$navbarSystem = $params['menu']['navbar']['items']['system'];
$navbarSystemChilds = $navbarSystem->getChildItems();
$navbarSystemChilds['rbac'] = $params['rbacNavbarMenuItem'];
$navbarSystem->setChildItems($navbarSystemChilds);

return [
    ManagerInterface::class => new ManagerFactory($params['rbac']),
    AccessCheckerInterface::class => Reference::to(ManagerInterface::class),
];
