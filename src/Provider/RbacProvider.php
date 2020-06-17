<?php

declare(strict_types=1);

/**
 * Rbac module for Mailery Platform
 * @link      https://github.com/maileryio/mailery-rbac
 * @package   Mailery\Rbac
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Rbac\Provider;

use Yiisoft\Aliases\Aliases;
use Yiisoft\Di\Container;
use Yiisoft\Di\Support\ServiceProvider;
use Yiisoft\Files\FileHelper;
use Yiisoft\Rbac\Manager;
use Yiisoft\Rbac\Php\Storage;
use Yiisoft\Rbac\RuleFactory\ClassNameRuleFactory;
use Yiisoft\Rbac\StorageInterface;

class RbacProvider extends ServiceProvider
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param Container $container
     * @throws \RuntimeException
     * @return void
     */
    public function register(Container $container): void
    {
        $aliases = $container->get(Aliases::class);

        $directory = $aliases->get($this->config['directory']);
        if (!is_dir($directory) && !FileHelper::createDirectory($directory)) {
            throw new \RuntimeException('Unable to create directory: ' . $directory);
        }

        $storage = new Storage($directory);
        $manager = new Manager($storage, new ClassNameRuleFactory());

        $manager->setDefaultRoles($this->config['defaultRoles']);

        $container->set(Manager::class, $manager);
        $container->set(StorageInterface::class, $storage);
    }
}
