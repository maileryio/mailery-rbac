<?php

declare(strict_types=1);

/**
 * Rbac module for Mailery Platform
 * @link      https://github.com/maileryio/mailery-rbac
 * @package   Mailery\Rbac
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Rbac\Controller;

use Mailery\Rbac\Form\PermissionForm;
use Yiisoft\Data\Paginator\OffsetPaginator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;
use Yiisoft\Http\Method;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface;
use Yiisoft\Rbac\StorageInterface as RbacStorage;

class PermissionController
{
    /**
     * @var ViewRenderer
     */
    private ViewRenderer $viewRenderer;

    /**
     * @var ResponseFactoryInterface
     */
    private ResponseFactoryInterface $responseFactory;

    /**
     * @var RbacStorage
     */
    private RbacStorage $rbacStorage;

    /**
     * @param ViewRenderer $viewRenderer
     * @param ResponseFactoryInterface $responseFactory
     * @param RbacStorage $rbacStorage
     */
    public function __construct(
        ViewRenderer $viewRenderer,
        ResponseFactoryInterface $responseFactory,
        RbacStorage $rbacStorage
    ) {
        $this->viewRenderer = $viewRenderer
            ->withController($this)
            ->withViewBasePath(dirname(dirname(__DIR__)) . '/views')
            ->withCsrf();

        $this->responseFactory = $responseFactory;
        $this->rbacStorage = $rbacStorage;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $queryParams = $request->getQueryParams();

        $dataReader = (new IterableDataReader($this->rbacStorage->getPermissions()))
            ->withLimit(1000);

        $paginator = (new OffsetPaginator($dataReader))
            ->withCurrentPage($queryParams['page'] ?? 1);

        return $this->viewRenderer->render('index', compact('dataReader', 'paginator'));
    }

    /**
     * @param Request $request
     * @param PermissionForm $permissionForm
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     */
    public function create(Request $request, PermissionForm $permissionForm, UrlGeneratorInterface $urlGenerator): Response
    {
        $permissionForm
            ->setAttributes([
                'action' => $request->getUri()->getPath(),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ])
        ;

        $submitted = $request->getMethod() === Method::POST;

        if ($submitted) {
            $permissionForm->loadFromServerRequest($request);

            if (($permission = $permissionForm->save()) !== null) {
                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', $urlGenerator->generate('/rbac/permission/view', ['name' => $permission->getName()]));
            }
        }

        return $this->viewRenderer->render('create', compact('permissionForm', 'submitted'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function view(Request $request): Response
    {
        $name = $request->getAttribute('name');
        if (empty($name) || ($permission = $this->rbacStorage->getPermissionByName($name)) === null) {
            return $this->responseFactory
                ->createResponse(404);
        }

        return $this->viewRenderer->render('view', compact('permission'));
    }

    /**
     * @param Request $request
     * @param PermissionForm $permissionForm
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     */
    public function edit(Request $request, PermissionForm $permissionForm, UrlGeneratorInterface $urlGenerator): Response
    {
        $name = $request->getAttribute('name');
        if (empty($name) || ($permission = $this->rbacStorage->getPermissionByName($name)) === null) {
            return $this->responseFactory
                ->createResponse(404);
        }

        $permissionForm
            ->withPermission($permission)
            ->setAttributes([
                'action' => $request->getUri()->getPath(),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ])
        ;

        $submitted = $request->getMethod() === Method::POST;

        if ($submitted) {
            $permissionForm->loadFromServerRequest($request);

            if (($newPermission = $permissionForm->save()) !== null) {
                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', $urlGenerator->generate('/rbac/permission/view', ['name' => $newPermission->getName()]));
            }
        }

        return $this->viewRenderer->render('edit', compact('permission', 'permissionForm', 'submitted'));
    }

    /**
     * @param Request $request
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     */
    public function delete(Request $request, UrlGeneratorInterface $urlGenerator): Response
    {
        $name = $request->getAttribute('name');
        if (empty($name) || ($permission = $this->rbacStorage->getPermissionByName($name)) === null) {
            return $this->responseFactory
                ->createResponse(404);
        }

        $this->rbacStorage->removeItem($permission);

        return $this->responseFactory
            ->createResponse()
            ->withStatus(303)
            ->withHeader('Location', $urlGenerator->generate('/rbac/permission/index'));
    }
}
