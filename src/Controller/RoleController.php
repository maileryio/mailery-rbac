<?php

namespace Mailery\Rbac\Controller;

use Mailery\Rbac\Controller;
use Mailery\Web\Exception\NotFoundHttpException;
use Mailery\Rbac\Form\RoleForm;
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

class RoleController extends Controller
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

        $dataReader = (new IterableDataReader($this->rbacManager->getRoles()))
            ->withLimit(1000); // temporary hack

        $paginator = (new OffsetPaginator($dataReader))
            ->withCurrentPage($queryParams['page'] ?? 1);

        $output = $this->render('index', compact('dataReader', 'paginator'));

        $response->getBody()->write($output);

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RoleForm $roleForm
     * @param UrlGeneratorInterface $urlGenerator
     * @return ResponseInterface
     */
    public function create(ServerRequestInterface $request, RoleForm $roleForm, UrlGeneratorInterface $urlGenerator): ResponseInterface
    {
        $response = $this->getResponseFactory()->createResponse();

        $roleForm
            ->setAttributes([
                'action' => $request->getUri()->getPath(),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ]);

        $submitted = $request->getMethod() === Method::POST;

        if ($submitted) {
            $roleForm->loadFromServerRequest($request);

            if ($roleForm->isValid() && ($role = $roleForm->save()) !== null) {
                return $response
                    ->withStatus(302)
                    ->withHeader('Location', $urlGenerator->generate('/rbac/role/view', ['name' => $role->getName()]));
            }
        }

        $output = $this->render('create', compact('roleForm', 'submitted'));

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
        if (empty($name) || ($role = $this->rbacManager->getRole($name)) === null) {
            throw new NotFoundHttpException();
        }

        $output = $this->render('view', compact('role'));

        $response->getBody()->write($output);

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RoleForm $roleForm
     * @param UrlGeneratorInterface $urlGenerator
     * @return ResponseInterface
     * @throws NotFoundHttpException
     */
    public function edit(ServerRequestInterface $request, RoleForm $roleForm, UrlGeneratorInterface $urlGenerator): ResponseInterface
    {
        $response = $this->getResponseFactory()->createResponse();

        $name = $request->getAttribute('name');
        if (empty($name) || ($role = $this->rbacManager->getRole($name)) === null) {
            throw new NotFoundHttpException();
        }

        $roleForm
            ->setAttributes([
                'action' => $request->getUri()->getPath(),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ])
            ->withRole($role);

        $submitted = $request->getMethod() === Method::POST;

        if ($submitted) {
            $roleForm->loadFromServerRequest($request);

            if ($roleForm->isValid() && ($role = $roleForm->save()) !== null) {
                return $response
                    ->withStatus(302)
                    ->withHeader('Location', $urlGenerator->generate('/rbac/role/view', ['name' => $role->getName()]));
            }
        }

        $output = $this->render('edit', compact('role', 'roleForm', 'submitted'));

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
        if (empty($name) || ($role = $this->rbacManager->getRole($name)) === null) {
            throw new NotFoundHttpException();
        }

        $this->rbacManager->remove($role);

        return $response
            ->withStatus(303)
            ->withHeader('Location', $urlGenerator->generate('/rbac/role/index'));
    }

}
