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

use Mailery\Rbac\Controller;
use Mailery\Rbac\Form\RoleForm;
use Mailery\Widget\Dataview\Paginator\OffsetPaginator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;
use Yiisoft\Http\Method;
use Yiisoft\Router\UrlGeneratorInterface;

class RoleController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $queryParams = $request->getQueryParams();

        $dataReader = (new IterableDataReader($this->getRbacManager()->getRoles()))
            ->withLimit(1000);

        $paginator = (new OffsetPaginator($dataReader))
            ->withCurrentPage($queryParams['page'] ?? 1);

        return $this->render('index', compact('dataReader', 'paginator'));
    }

    /**
     * @param Request $request
     * @param RoleForm $roleForm
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     */
    public function create(Request $request, RoleForm $roleForm, UrlGeneratorInterface $urlGenerator): Response
    {
        $roleForm
            ->setAttributes([
                'action' => $request->getUri()->getPath(),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ])
        ;

        $submitted = $request->getMethod() === Method::POST;

        if ($submitted) {
            $roleForm->loadFromServerRequest($request);

            if (($role = $roleForm->save()) !== null) {
                return $this->getResponseFactory()
                    ->createResponse(302)
                    ->withHeader('Location', $urlGenerator->generate('/rbac/role/view', ['name' => $role->getName()]));
            }
        }

        return $this->render('create', compact('roleForm', 'submitted'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function view(Request $request): Response
    {
        $name = $request->getAttribute('name');
        if (empty($name) || ($role = $this->getRbacManager()->getRole($name)) === null) {
            return $this->getResponseFactory()
                ->createResponse(404);
        }

        return $this->render('view', compact('role'));
    }

    /**
     * @param Request $request
     * @param RoleForm $roleForm
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     */
    public function edit(Request $request, RoleForm $roleForm, UrlGeneratorInterface $urlGenerator): Response
    {
        $name = $request->getAttribute('name');
        if (empty($name) || ($role = $this->getRbacManager()->getRole($name)) === null) {
            return $this->getResponseFactory()
                ->createResponse(404);
        }

        $roleForm
            ->withRole($role)
            ->setAttributes([
                'action' => $request->getUri()->getPath(),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ])
        ;

        $submitted = $request->getMethod() === Method::POST;

        if ($submitted) {
            $roleForm->loadFromServerRequest($request);

            if ($roleForm->save() !== null) {
                return $this->getResponseFactory()
                    ->createResponse(302)
                    ->withHeader('Location', $urlGenerator->generate('/rbac/role/view', ['name' => $role->getName()]));
            }
        }

        return $this->render('edit', compact('role', 'roleForm', 'submitted'));
        ;
    }

    /**
     * @param Request $request
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     */
    public function delete(Request $request, UrlGeneratorInterface $urlGenerator): Response
    {
        $name = $request->getAttribute('name');
        if (empty($name) || ($role = $this->getRbacManager()->getRole($name)) === null) {
            return $this->getResponseFactory()
                ->createResponse(404);
        }

        $this->getRbacManager()->remove($role);

        return $this->getResponseFactory()
            ->createResponse(303)
            ->withHeader('Location', $urlGenerator->generate('/rbac/role/index'));
    }
}
