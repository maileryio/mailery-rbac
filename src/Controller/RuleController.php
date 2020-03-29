<?php

namespace Mailery\Rbac\Controller;

use Mailery\Rbac\Controller;
use Mailery\Rbac\Form\RuleForm;
use Mailery\Widget\Dataview\Paginator\OffsetPaginator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;
use Yiisoft\Http\Method;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Rbac\ManagerInterface as RbacManager;
use Yiisoft\Rbac\Rule;
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
        $queryParams = $request->getQueryParams();

        $dataReader = (new IterableDataReader($this->rbacManager->getRules()))
            ->withLimit(1000); // temporary hack

        $paginator = (new OffsetPaginator($dataReader))
            ->withCurrentPage($queryParams['page'] ?? 1);

        return $this->render('index', compact('dataReader', 'paginator'));
    }

    /**
     * @param ServerRequestInterface $request
     * @param RuleForm $ruleForm
     * @param UrlGeneratorInterface $urlGenerator
     * @return ResponseInterface
     */
    public function create(ServerRequestInterface $request, RuleForm $ruleForm, UrlGeneratorInterface $urlGenerator): ResponseInterface
    {
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
                return $this->getResponseFactory()
                    ->createResponse(302)
                    ->withHeader('Location', $urlGenerator->generate('/rbac/rule/view', ['name' => $rule->getName()]));
            }
        }

        return $this->render('create', compact('ruleForm', 'submitted'));
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function view(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getAttribute('name');
        if (empty($name) || ($rule = $this->rbacManager->getRule($name)) === null) {
            return $this->getResponseFactory()
                ->createResponse(404);
        }

        return $this->render('view', compact('rule'));
    }

    /**
     * @param ServerRequestInterface $request
     * @param RuleForm $ruleForm
     * @param UrlGeneratorInterface $urlGenerator
     * @return ResponseInterface
     */
    public function edit(ServerRequestInterface $request, RuleForm $ruleForm, UrlGeneratorInterface $urlGenerator): ResponseInterface
    {
        $name = $request->getAttribute('name');
        if (empty($name) || ($rule = $this->rbacManager->getRule($name)) === null) {
            return $this->getResponseFactory()
                ->createResponse(404);
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
                return $this->getResponseFactory()
                    ->createResponse(302)
                    ->withHeader('Location', $urlGenerator->generate('/rbac/rule/view', ['name' => $rule->getName()]));
            }
        }

        return $this->render('edit', compact('rule', 'ruleForm', 'submitted'));
    }

    /**
     * @param ServerRequestInterface $request
     * @param UrlGeneratorInterface $urlGenerator
     * @return ResponseInterface
     */
    public function delete(ServerRequestInterface $request, UrlGeneratorInterface $urlGenerator): ResponseInterface
    {
        $name = $request->getAttribute('name');
        if (empty($name) || ($rule = $this->rbacManager->getRule($name)) === null) {
            return $this->getResponseFactory()
                ->createResponse(404);
        }

        $this->rbacManager->remove($rule);

        return $this->getResponseFactory()
            ->createResponse(302)
            ->withHeader('Location', $urlGenerator->generate('/rbac/rule/index'));
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function suggestions(ServerRequestInterface $request): ResponseInterface
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
                    $this->rbacManager->getRules(),
                    function (Rule $rule) use($query) {
                        return strpos($rule->getName(), $query) !== false;
                    }
                )
            );
        }

        return $this->asJson(array_values($data));
    }

}
