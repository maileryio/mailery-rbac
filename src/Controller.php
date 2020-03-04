<?php

namespace Mailery\Rbac;

use Mailery\Web\Controller as WebController;
use Psr\Http\Message\ResponseFactoryInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\View\WebView;

abstract class Controller extends WebController
{
    /**
     * @param ResponseFactoryInterface $responseFactory
     * @param Aliases $aliases
     * @param WebView $view
     */
    public function __construct(ResponseFactoryInterface $responseFactory, Aliases $aliases, WebView $view)
    {
        parent::__construct($responseFactory, $aliases, $view);

        $this->setBaseViewPath(dirname(__DIR__) . '/views');
    }
}
