<?php

declare(strict_types=1);

/**
 * Rbac module for Mailery Platform
 * @link      https://github.com/maileryio/mailery-rbac
 * @package   Mailery\Rbac
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Rbac;

use Cycle\ORM\ORMInterface;
use Mailery\Brand\Service\BrandLocator;
use Mailery\Common\Web\Controller;
use Mailery\Rbac\Assets\RbacAssetBundle;
use Mailery\Web\Assets\AppAssetBundle;
use Psr\Http\Message\ResponseFactoryInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Rbac\Manager as RbacManager;
use Yiisoft\Rbac\StorageInterface as RbacStorage;
use Yiisoft\View\WebView;

abstract class WebController extends Controller
{
    /**
     * @var RbacManager
     */
    private RbacManager $rbacManager;

    /**
     * @var RbacStorage
     */
    private RbacStorage $rbacStorage;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        AssetManager $assetManager,
        RbacManager $rbacManager,
        RbacStorage $rbacStorage,
        BrandLocator $brandLocator,
        ResponseFactoryInterface $responseFactory,
        Aliases $aliases,
        WebView $view,
        ORMInterface $orm
    ) {
        $bundle = $assetManager->getBundle(AppAssetBundle::class);
        $bundle->depends[] = RbacAssetBundle::class;

        $this->rbacManager = $rbacManager;
        $this->rbacStorage = $rbacStorage;
        parent::__construct($brandLocator, $responseFactory, $aliases, $view, $orm);
    }

    /**
     * @return RbacManager
     */
    protected function getRbacManager(): RbacManager
    {
        return $this->rbacManager;
    }

    /**
     * @return RbacStorage
     */
    protected function getRbacStorage(): RbacStorage
    {
        return $this->rbacStorage;
    }
}
