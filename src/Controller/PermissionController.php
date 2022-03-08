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
use Yiisoft\Http\Status;
use Yiisoft\Http\Header;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface;
use Yiisoft\Rbac\StorageInterface as RbacStorage;
use Mailery\Rbac\Service\PermissionCrudService;
use Mailery\Rbac\ValueObject\PermissionValueObject;
use Yiisoft\Validator\ValidatorInterface;

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
     * @var UrlGeneratorInterface
     */
    private UrlGeneratorInterface $urlGenerator;

    /**
     * @var RbacStorage
     */
    private RbacStorage $rbacStorage;

    /**
     * @var PermissionCrudService
     */
    private PermissionCrudService $permissionCrudService;

    /**
     * @param ViewRenderer $viewRenderer
     * @param ResponseFactoryInterface $responseFactory
     * @param UrlGeneratorInterface $urlGenerator
     * @param RbacStorage $rbacStorage
     * @param PermissionCrudService $permissionCrudService
     */
    public function __construct(
        ViewRenderer $viewRenderer,
        ResponseFactoryInterface $responseFactory,
        UrlGeneratorInterface $urlGenerator,
        RbacStorage $rbacStorage,
        PermissionCrudService $permissionCrudService
    ) {
        $this->viewRenderer = $viewRenderer
            ->withController($this)
            ->withViewPath(dirname(dirname(__DIR__)) . '/views');

        $this->responseFactory = $responseFactory;
        $this->urlGenerator = $urlGenerator;
        $this->rbacStorage = $rbacStorage;
        $this->permissionCrudService = $permissionCrudService;
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
     * @return Response
     */
    public function view(Request $request): Response
    {
        $name = $request->getAttribute('name');
        if (empty($name) || ($permission = $this->rbacStorage->getPermissionByName($name)) === null) {
            return $this->responseFactory
                ->createResponse(Status::NOT_FOUND);
        }

        return $this->viewRenderer->render('view', compact('permission'));
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param PermissionForm $form
     * @return Response
     */
    public function create(Request $request, ValidatorInterface $validator, PermissionForm $form): Response
    {
        $body = $request->getParsedBody();

        if (($request->getMethod() === Method::POST) && $form->load($body) && $validator->validate($form)->isValid()) {
            $valueObject = PermissionValueObject::fromForm($form);
            $permission = $this->permissionCrudService->create($valueObject);

            return $this->responseFactory
                ->createResponse(Status::FOUND)
                ->withHeader(Header::LOCATION, $this->urlGenerator->generate('/rbac/permission/view', ['name' => $permission->getName()]));
        }

        return $this->viewRenderer->render('create', compact('form'));
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param PermissionForm $form
     * @return Response
     */
    public function edit(Request $request, ValidatorInterface $validator, PermissionForm $form): Response
    {
        $body = $request->getParsedBody();
        $name = $request->getAttribute('name');
        if (empty($name) || ($permission = $this->rbacStorage->getPermissionByName($name)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $form = $form->withPermission($permission);

        if (($request->getMethod() === Method::POST) && $form->load($body) && $validator->validate($form)->isValid()) {
            $valueObject = PermissionValueObject::fromForm($form);
            $permission = $this->permissionCrudService->update($permission, $valueObject);

            return $this->responseFactory
                    ->createResponse(Status::FOUND)
                    ->withHeader(Header::LOCATION, $this->urlGenerator->generate('/rbac/permission/view', ['name' => $permission->getName()]));
        }

        return $this->viewRenderer->render('edit', compact('form', 'permission'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request): Response
    {
        $name = $request->getAttribute('name');
        if (empty($name) || ($permission = $this->rbacStorage->getPermissionByName($name)) === null) {
            return $this->responseFactory
                ->createResponse(Status::NOT_FOUND);
        }

        $this->rbacStorage->removeItem($permission);

        return $this->responseFactory
            ->createResponse(303)
            ->withHeader(Header::LOCATION, $this->urlGenerator->generate('/rbac/permission/index'));
    }
}
