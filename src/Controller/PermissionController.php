<?php

namespace Mailery\Rbac\Controller;

use Mailery\Rbac\Controller;
use Mailery\Web\Exception\NotFoundHttpException;
use Mailery\Rbac\Form\PermissionForm;
use Mailery\Dataview\Paginator\OffsetPaginator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;
use Yiisoft\Http\Method;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Rbac\ManagerInterface as RbacManager;
use Yiisoft\View\WebView;

class PermissionController extends Controller
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
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->getResponseFactory()->createResponse();

        $queryParams = $request->getQueryParams();

        $dataReader = (new IterableDataReader($this->rbacManager->getPermissions()))
            ->withLimit(1000); // temporary hack

        $paginator = (new OffsetPaginator($dataReader))
            ->withCurrentPage($queryParams['page'] ?? 1);

        $output = $this->render('index', compact('dataReader', 'paginator'));

        $response->getBody()->write($output);

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param PermissionForm $permissionForm
     * @param UrlGeneratorInterface $urlGenerator
     * @return ResponseInterface
     */
    public function create(ServerRequestInterface $request, PermissionForm $permissionForm, UrlGeneratorInterface $urlGenerator): ResponseInterface
    {
        $response = $this->getResponseFactory()->createResponse();

        $permissionForm
            ->setAttributes([
                'action' => $request->getUri()->getPath(),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ]);

        $submitted = $request->getMethod() === Method::POST;

        if ($submitted) {
            $permissionForm->loadFromServerRequest($request);

            if ($permissionForm->isValid() && ($permission = $permissionForm->save()) !== null) {
                return $response
                    ->withStatus(302)
                    ->withHeader('Location', $urlGenerator->generate('/rbac/permission/view', ['name' => $permission->getName()]));
            }
        }

        $output = $this->render('create', compact('permissionForm', 'submitted'));

        $response->getBody()->write($output);

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function view(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->getResponseFactory()->createResponse();

        $name = $request->getAttribute('name');
        if (empty($name) || ($permission = $this->rbacManager->getPermission($name)) === null) {
            throw new NotFoundHttpException();
        }

        $output = $this->render('view', compact('permission'));

        $response->getBody()->write($output);

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param PermissionForm $permissionForm
     * @param UrlGeneratorInterface $urlGenerator
     * @return ResponseInterface
     * @throws NotFoundHttpException
     */
    public function edit(ServerRequestInterface $request, PermissionForm $permissionForm, UrlGeneratorInterface $urlGenerator): ResponseInterface
    {
        $response = $this->getResponseFactory()->createResponse();

        $name = $request->getAttribute('name');
        if (empty($name) || ($permission = $this->rbacManager->getPermission($name)) === null) {
            throw new NotFoundHttpException();
        }

        $permissionForm
            ->setAttributes([
                'action' => $request->getUri()->getPath(),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ])
            ->withPermission($permission);

        $submitted = $request->getMethod() === Method::POST;

        if ($submitted) {
            $permissionForm->loadFromServerRequest($request);

            if ($permissionForm->isValid() && ($permission = $permissionForm->save()) !== null) {
                return $response
                    ->withStatus(302)
                    ->withHeader('Location', $urlGenerator->generate('/rbac/permission/view', ['name' => $permission->getName()]));
            }
        }

        $output = $this->render('edit', compact('permission', 'permissionForm', 'submitted'));

        $response->getBody()->write($output);

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param UrlGeneratorInterface $urlGenerator
     * @return ResponseInterface
     * @throws NotFoundHttpException
     */
    public function delete(ServerRequestInterface $request, UrlGeneratorInterface $urlGenerator): ResponseInterface
    {
        $response = $this->getResponseFactory()->createResponse();

        $name = $request->getAttribute('name');
        if (empty($name) || ($permission = $this->rbacManager->getPermission($name)) === null) {
            throw new NotFoundHttpException();
        }

        $this->rbacManager->remove($permission);

        return $response
            ->withStatus(303)
            ->withHeader('Location', $urlGenerator->generate('/rbac/permission/index'));
    }

}
