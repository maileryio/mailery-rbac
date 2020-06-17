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
use Mailery\Rbac\WebController;
use Mailery\Widget\Dataview\Paginator\OffsetPaginator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;
use Yiisoft\Http\Method;
use Yiisoft\Router\UrlGeneratorInterface;

class PermissionController extends WebController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $queryParams = $request->getQueryParams();

        $dataReader = (new IterableDataReader($this->getRbacStorage()->getPermissions()))
            ->withLimit(1000);

        $paginator = (new OffsetPaginator($dataReader))
            ->withCurrentPage($queryParams['page'] ?? 1);

        return $this->render('index', compact('dataReader', 'paginator'));
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
                return $this->getResponseFactory()
                    ->createResponse(302)
                    ->withHeader('Location', $urlGenerator->generate('/rbac/permission/view', ['name' => $permission->getName()]));
            }
        }

        return $this->render('create', compact('permissionForm', 'submitted'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function view(Request $request): Response
    {
        $name = $request->getAttribute('name');
        if (empty($name) || ($permission = $this->getRbacStorage()->getPermissionByName($name)) === null) {
            return $this->getResponseFactory()
                ->createResponse(404);
        }

        return $this->render('view', compact('permission'));
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
        if (empty($name) || ($permission = $this->getRbacStorage()->getPermissionByName($name)) === null) {
            return $this->getResponseFactory()
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
                return $this->getResponseFactory()
                    ->createResponse(302)
                    ->withHeader('Location', $urlGenerator->generate('/rbac/permission/view', ['name' => $newPermission->getName()]));
            }
        }

        return $this->render('edit', compact('permission', 'permissionForm', 'submitted'));
    }

    /**
     * @param Request $request
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     */
    public function delete(Request $request, UrlGeneratorInterface $urlGenerator): Response
    {
        $response = $this->getResponseFactory()->createResponse();

        $name = $request->getAttribute('name');
        if (empty($name) || ($permission = $this->getRbacStorage()->getPermissionByName($name)) === null) {
            return $this->getResponseFactory()
                ->createResponse(404);
        }

        $this->getRbacStorage()->removeItem($permission);

        return $response
            ->withStatus(303)
            ->withHeader('Location', $urlGenerator->generate('/rbac/permission/index'));
    }
}
