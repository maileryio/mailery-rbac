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

use Mailery\Common\Web\Controller;
use Psr\Http\Message\ResponseFactoryInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\View\WebView;
use Mailery\Brand\Service\BrandLocator;
use Cycle\ORM\ORMInterface;
use Yiisoft\Assets\AssetManager;
use Mailery\Web\Assets\AppAssetBundle;
use Mailery\Rbac\Assets\RbacAssetBundle;
use Yiisoft\Rbac\StorageInterface as RbacStorage;
use Yiisoft\Rbac\Manager as RbacManager;

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
     * @inheritdoc
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
