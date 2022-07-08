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
use Yiisoft\Rbac\AssignmentsStorageInterface;
use Yiisoft\Rbac\ItemsStorageInterface;
use Yiisoft\Rbac\Manager;
use Yiisoft\Rbac\Php\AssignmentsStorage;
use Yiisoft\Rbac\Php\ItemsStorage;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Definitions\DynamicReference;

/** @var array $params */

return [
    AssignmentsStorageInterface::class => [
        'class' => AssignmentsStorage::class,
        '__construct()' => [
            'directory' => DynamicReference::to(static function (Aliases $aliases) use($params) {
                return $aliases->get($params['mailery/mailery-rbac']['storageDirectory']);
            }),
        ],
    ],
    ItemsStorageInterface::class => [
        'class' => ItemsStorage::class,
        '__construct()' => [
            'directory' => DynamicReference::to(static function (Aliases $aliases) use($params) {
                return $aliases->get($params['mailery/mailery-rbac']['storageDirectory']);
            }),
        ],
    ],
    AccessCheckerInterface::class => Manager::class,
];