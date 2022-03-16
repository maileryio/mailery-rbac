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

use Mailery\Rbac\Form\RuleForm;
use Yiisoft\Data\Paginator\OffsetPaginator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;
use Yiisoft\Http\Method;
use Yiisoft\Http\Status;
use Yiisoft\Http\Header;
use Yiisoft\Rbac\Rule;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface;
use Yiisoft\Rbac\StorageInterface as RbacStorage;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\DataResponse\Formatter\JsonDataResponseFormatter;
use Mailery\Rbac\ValueObject\RuleValueObject;
use Mailery\Rbac\Service\RuleCrudService;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\Router\CurrentRoute;

class RuleController
{
    /**
     * @param ViewRenderer $viewRenderer
     * @param ResponseFactoryInterface $responseFactory
     * @param UrlGeneratorInterface $urlGenerator
     * @param DataResponseFactoryInterface $dataResponseFactory
     * @param RbacStorage $rbacStorage
     * @param RuleCrudService $ruleCrudService
     */
    public function __construct(
        private ViewRenderer $viewRenderer,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
        private DataResponseFactoryInterface $dataResponseFactory,
        private RbacStorage $rbacStorage,
        private RuleCrudService $ruleCrudService
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

        $dataReader = (new IterableDataReader($this->rbacStorage->getRules()))
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
        if (empty($name) || ($rule = $this->rbacStorage->getRuleByName($name)) === null) {
            return $this->responseFactory
                ->createResponse(Status::NOT_FOUND);
        }

        return $this->viewRenderer->render('view', compact('rule'));
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param RuleForm $form
     * @return Response
     */
    public function create(Request $request, ValidatorInterface $validator, RuleForm $form): Response
    {
        $body = $request->getParsedBody();

        if (($request->getMethod() === Method::POST) && $form->load($body) && $validator->validate($form)->isValid()) {
            $valueObject = RuleValueObject::fromForm($form);
            $rule = $this->ruleCrudService->create($valueObject);

            return $this->responseFactory
                ->createResponse(Status::FOUND)
                ->withHeader(Header::LOCATION, $this->urlGenerator->generate('/rbac/rule/view', ['name' => $rule->getName()]));
        }

        return $this->viewRenderer->render('create', compact('form'));
    }

    /**
     * @param Request $request
     * @param CurrentRoute $currentRoute
     * @param ValidatorInterface $validator
     * @param RuleForm $form
     * @return Response
     */
    public function edit(Request $request, CurrentRoute $currentRoute, ValidatorInterface $validator, RuleForm $form): Response
    {
        $body = $request->getParsedBody();
        $name = $currentRoute->getArgument('name');
        if (empty($name) || ($rule = $this->rbacStorage->getRuleByName($name)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $form = $form->withRule($rule);

        if (($request->getMethod() === Method::POST) && $form->load($body) && $validator->validate($form)->isValid()) {
            $valueObject = RuleValueObject::fromForm($form);
            $rule = $this->ruleCrudService->update($rule, $valueObject);

            return $this->responseFactory
                    ->createResponse(Status::FOUND)
                    ->withHeader(Header::LOCATION, $this->urlGenerator->generate('/rbac/rule/view', ['name' => $rule->getName()]));
        }

        return $this->viewRenderer->render('edit', compact('form', 'rule'));
    }

    /**
     * @param CurrentRoute $currentRoute
     * @return Response
     */
    public function delete(CurrentRoute $currentRoute): Response
    {
        $name = $currentRoute->getArgument('name');
        if (empty($name) || ($rule = $this->rbacStorage->getRuleByName($name)) === null) {
            return $this->responseFactory
                ->createResponse(Status::NOT_FOUND);
        }

        $this->rbacStorage->removeRule($rule->getName());

        return $this->responseFactory
            ->createResponse(Status::SEE_OTHER)
            ->withHeader(Header::LOCATION, $this->urlGenerator->generate('/rbac/rule/index'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function suggestions(Request $request): Response
    {
        $data = [];
        $queryParams = $request->getQueryParams();

        if (!empty($queryParams['q'])) {
            $query = $queryParams['q'];
            $data = array_map(
                function (Rule $rule) {
                    return [
                        'id' => $rule->getName(),
                        'text' => $rule->getName(),
                    ];
                },
                array_filter(
                    $this->rbacStorage->getRules(),
                    function (Rule $rule) use ($query) {
                        return strpos($rule->getName(), $query) !== false;
                    }
                )
            );
        }

        return $this->dataResponseFactory
            ->createResponse(array_values($data), Status::FOUND)
            ->withResponseFormatter(new JsonDataResponseFormatter());
    }
}
