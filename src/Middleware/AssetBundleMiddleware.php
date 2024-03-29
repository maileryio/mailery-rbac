<?php

namespace Mailery\Rbac\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Mailery\Assets\AssetBundleRegistry;
use Mailery\Rbac\Assets\RbacAssetBundle;

class AssetBundleMiddleware implements MiddlewareInterface
{
    /**
     * @param AssetBundleRegistry $assetBundleRegistry
     */
    public function __construct(
        private AssetBundleRegistry $assetBundleRegistry
    ) {}

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->assetBundleRegistry->add(RbacAssetBundle::class);

        return $handler->handle($request);
    }
}
