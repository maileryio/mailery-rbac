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

use Mailery\Rbac\Form\RoleForm;
use Yiisoft\Data\Paginator\OffsetPaginator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;
use Yiisoft\Http\Method;
use Yiisoft\Http\Status;
use Yiisoft\Http\Header;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface;
use Yiisoft\Rbac\StorageInterface as RbacStorage;
use Yiisoft\Validator\ValidatorInterface;
use Mailery\Rbac\ValueObject\RoleValueObject;
use Mailery\Rbac\Service\RoleCrudService;
use Yiisoft\Router\CurrentRoute;

class RoleController
{
    /**
     * @param ViewRenderer $viewRenderer
     * @param ResponseFactoryInterface $responseFactory
     * @param UrlGeneratorInterface $urlGenerator
     * @param RbacStorage $rbacStorage
     * @param RoleCrudService $roleCrudService
     */
    public function __construct(
        private ViewRenderer $viewRenderer,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
        private RbacStorage $rbacStorage,
        private RoleCrudService $roleCrudService
    ) {
        $this->viewRenderer = $viewRenderer
            ->withController($this)
            ->withViewPath(dirname(dirname(__DIR__)) . '/views');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $queryParams = $request->getQueryParams();

        $dataReader = (new IterableDataReader($this->rbacStorage->getRoles()))
            ->withLimit(1000);

        $paginator = (new OffsetPaginator($dataReader))
            ->withCurrentPage($queryParams['page'] ?? 1);

        return $this->viewRenderer->render('index', compact('dataReader', 'paginator'));
    }

    /**
     * @param CurrentRoute $currentRoute
     * @return Response
     */
    public function view(CurrentRoute $currentRoute): Response
    {
        $name = $currentRoute->getArgument('name');
        if (empty($name) || ($role = $this->rbacStorage->getRoleByName($name)) === null) {
            return $this->responseFactory
                ->createResponse(Status::NOT_FOUND);
        }

        return $this->viewRenderer->render('view', compact('role'));
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param RoleForm $form
     * @return Response
     */
    public function create(Request $request, ValidatorInterface $validator, RoleForm $form): Response
    {
        $body = $request->getParsedBody();

        if (($request->getMethod() === Method::POST) && $form->load($body) && $validator->validate($form)->isValid()) {
            $valueObject = RoleValueObject::fromForm($form);
            $role = $this->roleCrudService->create($valueObject);

            return $this->responseFactory
                ->createResponse(Status::FOUND)
                ->withHeader(Header::LOCATION, $this->urlGenerator->generate('/rbac/role/view', ['name' => $role->getName()]));
        }

        return $this->viewRenderer->render('create', compact('form'));
    }

    /**
     * @param Request $request
     * @param CurrentRoute $currentRoute
     * @param ValidatorInterface $validator
     * @param RoleForm $form
     * @return Response
     */
    public function edit(Request $request, CurrentRoute $currentRoute, ValidatorInterface $validator, RoleForm $form): Response
    {
        $body = $request->getParsedBody();
        $name = $currentRoute->getArgument('name');
        if (empty($name) || ($role = $this->rbacStorage->getRoleByName($name)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $form = $form->withRole($role);

        if (($request->getMethod() === Method::POST) && $form->load($body) && $validator->validate($form)->isValid()) {
            $valueObject = RoleValueObject::fromForm($form);
            $role = $this->roleCrudService->update($role, $valueObject);

            return $this->responseFactory
                    ->createResponse(Status::FOUND)
                    ->withHeader(Header::LOCATION, $this->urlGenerator->generate('/rbac/role/view', ['name' => $role->getName()]));
        }

        return $this->viewRenderer->render('edit', compact('form', 'role'));
    }

    /**
     * @param CurrentRoute $currentRoute
     * @return Response
     */
    public function delete(CurrentRoute $currentRoute): Response
    {
        $name = $currentRoute->getArgument('name');
        if (empty($name) || ($role = $this->rbacStorage->getRoleByName($name)) === null) {
            return $this->responseFactory
                ->createResponse(Status::NOT_FOUND);
        }

        $this->rbacStorage->removeItem($role);

        return $this->responseFactory
            ->createResponse(Status::SEE_OTHER)
            ->withHeader(Header::LOCATION, $this->urlGenerator->generate('/rbac/role/index'));
    }
}
