<?php

namespace Mailery\Rbac\Controller;

use Mailery\Rbac\Controller;
use Mailery\Web\Exception\NotFoundHttpException;
use Mailery\Rbac\Form\RuleForm;
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

class RuleController extends Controller
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

        $dataReader = (new IterableDataReader($this->rbacManager->getRules()))
            ->withLimit(1000); // temporary hack

        $paginator = (new OffsetPaginator($dataReader))
            ->withCurrentPage($queryParams['page'] ?? 1);

        $output = $this->render('index', compact('dataReader', 'paginator'));

        $response->getBody()->write($output);

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RuleForm $ruleForm
     * @param UrlGeneratorInterface $urlGenerator
     * @return ResponseInterface
     */
    public function create(ServerRequestInterface $request, RuleForm $ruleForm, UrlGeneratorInterface $urlGenerator): ResponseInterface
    {
        $response = $this->getResponseFactory()->createResponse();

        $ruleForm
            ->setAttributes([
                'action' => $request->getUri()->getPath(),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ]);

        $submitted = $request->getMethod() === Method::POST;

        if ($submitted) {
            $ruleForm->loadFromServerRequest($request);

            if ($ruleForm->isValid() && ($rule = $ruleForm->save()) !== null) {
                return $response
                    ->withStatus(302)
                    ->withHeader('Location', $urlGenerator->generate('/rbac/rule/view', ['name' => $rule->getName()]));
            }
        }

        $output = $this->render('create', compact('ruleForm', 'submitted'));

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
        if (empty($name) || ($rule = $this->rbacManager->getRule($name)) === null) {
            throw new NotFoundHttpException();
        }

        $output = $this->render('view', compact('rule'));

        $response->getBody()->write($output);

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RuleForm $ruleForm
     * @param UrlGeneratorInterface $urlGenerator
     * @return ResponseInterface
     * @throws NotFoundHttpException
     */
    public function edit(ServerRequestInterface $request, RuleForm $ruleForm, UrlGeneratorInterface $urlGenerator): ResponseInterface
    {
        $response = $this->getResponseFactory()->createResponse();

        $name = $request->getAttribute('name');
        if (empty($name) || ($rule = $this->rbacManager->getRule($name)) === null) {
            throw new NotFoundHttpException();
        }

        $ruleForm
            ->setAttributes([
                'action' => $request->getUri()->getPath(),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ])
            ->withRule($rule);

        $submitted = $request->getMethod() === Method::POST;

        if ($submitted) {
            $ruleForm->loadFromServerRequest($request);

            if ($ruleForm->isValid() && ($rule = $ruleForm->save()) !== null) {
                return $response
                    ->withStatus(302)
                    ->withHeader('Location', $urlGenerator->generate('/rbac/rule/view', ['name' => $rule->getName()]));
            }
        }

        $output = $this->render('edit', compact('rule', 'ruleForm', 'submitted'));

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
        if (empty($name) || ($rule = $this->rbacManager->getRule($name)) === null) {
            throw new NotFoundHttpException();
        }

        $this->rbacManager->remove($rule);

        return $response
            ->withStatus(303)
            ->withHeader('Location', $urlGenerator->generate('/rbac/rule/index'));
    }

}
