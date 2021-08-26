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
use Yiisoft\Rbac\Rule;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface;
use Yiisoft\Rbac\StorageInterface as RbacStorage;
use Yiisoft\Yii\Api\ResponseFactory\JsonResponseFactory;

class RuleController
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
     * @var JsonResponseFactory
     */
    private JsonResponseFactory $jsonResponseFactory;

    /**
     * @var RbacStorage
     */
    private RbacStorage $rbacStorage;

    /**
     * @param ViewRenderer $viewRenderer
     * @param ResponseFactoryInterface $responseFactory
     * @param JsonResponseFactory $jsonResponseFactory
     * @param RbacStorage $rbacStorage
     */
    public function __construct(
        ViewRenderer $viewRenderer,
        ResponseFactoryInterface $responseFactory,
        JsonResponseFactory $jsonResponseFactory,
        RbacStorage $rbacStorage
    ) {
        $this->viewRenderer = $viewRenderer
            ->withController($this)
            ->withViewPath(dirname(dirname(__DIR__)) . '/views');

        $this->responseFactory = $responseFactory;
        $this->jsonResponseFactory = $jsonResponseFactory;
        $this->rbacStorage = $rbacStorage;
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
     * @param Request $request
     * @param RuleForm $ruleForm
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     */
    public function create(Request $request, RuleForm $ruleForm, UrlGeneratorInterface $urlGenerator): Response
    {
        $ruleForm
            ->setAttributes([
                'action' => $request->getUri()->getPath(),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ])
        ;

        $submitted = $request->getMethod() === Method::POST;

        if ($submitted) {
            $ruleForm->loadFromServerRequest($request);

            if (($rule = $ruleForm->save()) !== null) {
                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', $urlGenerator->generate('/rbac/rule/view', ['name' => $rule->getName()]));
            }
        }

        return $this->viewRenderer->render('create', compact('ruleForm', 'submitted'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function view(Request $request): Response
    {
        $name = $request->getAttribute('name');
        if (empty($name) || ($rule = $this->rbacStorage->getRuleByName($name)) === null) {
            return $this->responseFactory
                ->createResponse(404);
        }

        return $this->viewRenderer->render('view', compact('rule'));
    }

    /**
     * @param Request $request
     * @param RuleForm $ruleForm
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     */
    public function edit(Request $request, RuleForm $ruleForm, UrlGeneratorInterface $urlGenerator): Response
    {
        $name = $request->getAttribute('name');
        if (empty($name) || ($rule = $this->rbacStorage->getRuleByName($name)) === null) {
            return $this->responseFactory
                ->createResponse(404);
        }

        $ruleForm
            ->withRule($rule)
            ->setAttributes([
                'action' => $request->getUri()->getPath(),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ])
        ;

        $submitted = $request->getMethod() === Method::POST;

        if ($submitted) {
            $ruleForm->loadFromServerRequest($request);

            if (($newRule = $ruleForm->save()) !== null) {
                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', $urlGenerator->generate('/rbac/rule/view', ['name' => $newRule->getName()]));
            }
        }

        return $this->viewRenderer->render('edit', compact('rule', 'ruleForm', 'submitted'));
    }

    /**
     * @param Request $request
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     */
    public function delete(Request $request, UrlGeneratorInterface $urlGenerator): Response
    {
        $name = $request->getAttribute('name');
        if (empty($name) || ($rule = $this->rbacStorage->getRuleByName($name)) === null) {
            return $this->responseFactory
                ->createResponse(404);
        }

        $this->rbacStorage->removeRule($rule->getName());

        return $this->responseFactory
            ->createResponse(302)
            ->withHeader('Location', $urlGenerator->generate('/rbac/rule/index'));
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

        return $this->jsonResponseFactory
            ->createResponse(array_values($data));
    }
}
