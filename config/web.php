<?php

declare(strict_types=1);

/**
 * Rbac module for Mailery Platform
 * @link      https://github.com/maileryio/mailery-rbac
 * @package   Mailery\Rbac
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

use Yiisoft\Access\AccessCheckerInterface;
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\Rbac\Manager;

$navbarSystem = $params['menu']['navbar']['items']['system'];
$navbarSystemChilds = $navbarSystem->getChildItems();
$navbarSystemChilds['rbac'] = $params['rbacNavbarMenuItem'];
$navbarSystem->setChildItems($navbarSystemChilds);

return [
    AccessCheckerInterface::class => Reference::to(Manager::class),
];
