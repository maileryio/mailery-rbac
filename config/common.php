<?php

declare(strict_types=1);

/**
 * Rbac module for Mailery Platform
 * @link      https://github.com/maileryio/mailery-rbac
 * @package   Mailery\Rbac
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

use Yiisoft\Rbac\RuleFactory\ClassNameRuleFactory;
use Yiisoft\Rbac\RuleFactoryInterface;
use Yiisoft\Access\AccessCheckerInterface;
use Yiisoft\Rbac\Manager;
use Yiisoft\Rbac\StorageInterface;
use Yiisoft\Rbac\Php\Storage;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Definitions\DynamicReference;

/** @var array $params */

return [
    StorageInterface::class => [
        'class' => Storage::class,
        '__construct()' => [
            'directory' => DynamicReference::to(static function (Aliases $aliases) use($params) {
                return $aliases->get($params['mailery/mailery-rbac']['storageDirectory']);
            }),
        ],
    ],
    RuleFactoryInterface::class => ClassNameRuleFactory::class,
    AccessCheckerInterface::class => Manager::class,
];