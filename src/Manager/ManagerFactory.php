<?php

declare(strict_types=1);

/**
 * Rbac module for Mailery Platform
 * @link      https://github.com/maileryio/mailery-rbac
 * @package   Mailery\Rbac
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Rbac\Manager;

use Psr\Container\ContainerInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Files\FileHelper;
use Yiisoft\Rbac\Manager\PhpManager;
use Yiisoft\Rbac\RuleFactory\ClassNameRuleFactory;

class ManagerFactory
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
     * @param ContainerInterface $container
     * @throws \RuntimeException
     * @return PhpManager
     */
    public function __invoke(ContainerInterface $container)
    {
        $aliases = $container->get(Aliases::class);

        $directory = $aliases->get($this->config['directory']);
        if (!is_dir($directory) && !FileHelper::createDirectory($directory)) {
            throw new \RuntimeException('Unable to create directory: ' . $directory);
        }

        $rbacManager = new PhpManager(
            new ClassNameRuleFactory(),
            $directory
        );

        $rbacManager->setDefaultRoles($this->config['defaultRoles']);

        return $rbacManager;
    }
}
