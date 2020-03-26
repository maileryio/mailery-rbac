<?php

namespace Mailery\Rbac\Controller;

use Mailery\Rbac\Controller;
use Mailery\Web\Exception\NotFoundHttpException;
use Mailery\Rbac\Form\PermissionForm;
use Mailery\Widget\Dataview\Paginator\OffsetPaginator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;
use Yiisoft\Http\Method;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Rbac\ManagerInterface as RbacManager;
use Yiisoft\View\WebView;

class AssignController extends Controller
{
    /**
     * @var RbacManager
     */
    private RbacManager $rbacManager;

    /**
     * @param RbacManager $rbacManager
     * @param ResponseFactoryInterface $responseFactory
     * @param Aliases $aliases
     * @param WebView $view
     */
    public function __construct(RbacManager $rbacManager, ResponseFactoryInterface $responseFactory, Aliases $aliases, WebView $view)
    {
        $this->rbacManager = $rbacManager;
        parent::__construct($responseFactory, $aliases, $view);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function assigned(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->getResponseFactory()
            ->createResponse()
            ->withHeader('Content-Type', 'application/json');

        $response->getBody()->write('[{"text": "Item 1"}]');

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function unassigned(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->getResponseFactory()
            ->createResponse()
            ->withHeader('Content-Type', 'application/json');

        $response->getBody()->write('[{"text": "Item 1"}]');

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function assign(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->getResponseFactory()
            ->createResponse()
            ->withHeader('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function unassign(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->getResponseFactory()
            ->createResponse()
            ->withHeader('Content-Type', 'application/json');

        return $response;
    }
}
