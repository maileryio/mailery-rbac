<?php

namespace Mailery\Rbac\Controller;

use Mailery\Rbac\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Rbac\Item;
use Yiisoft\Rbac\ManagerInterface as RbacManager;
use Yiisoft\View\WebView;

class AssignController extends Controller
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
     * @param Request $request
     * @return Response
     */
    public function assigned(Request $request): Response
    {
        if (($currentItem = $this->getCurrentItem($request)) === null) {
            return $this->getResponseFactory()
                ->createResponse(404);
        }

        return $this->asJson($this->getAssignedItems($currentItem));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function unassigned(Request $request): Response
    {
        if (($currentItem = $this->getCurrentItem($request)) === null) {
            return $this->getResponseFactory()
                ->createResponse(404);
        }

        return $this->asJson($this->getUnassignedItems($currentItem));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function assign(Request $request): Response
    {
        if (($currentItem = $this->getCurrentItem($request)) === null) {
            return $this->getResponseFactory()
                ->createResponse(404);
        }

        $items = json_decode($request->getBody()->getContents(), true);

        foreach ($items as $item) {
            if (($childItem = $this->getItem($item['id'], $item['type'])) === null) {
                continue;
            }

            if ($this->rbacManager->canAddChild($currentItem, $childItem)
                && !$this->rbacManager->hasChild($currentItem, $childItem)) {
                $this->rbacManager->addChild($currentItem, $childItem);
            }
        }

        return $this->asJson([
            'success' => true,
            'data' => [
                'assigned' => $this->getAssignedItems($currentItem),
                'unassigned' => $this->getUnassignedItems($currentItem),
            ],
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function unassign(Request $request): Response
    {
        if (($currentItem = $this->getCurrentItem($request)) === null) {
            return $this->getResponseFactory()
                ->createResponse(404);
        }

        $items = json_decode($request->getBody()->getContents(), true);

        foreach ($items as $item) {
            if (($childItem = $this->getItem($item['id'], $item['type'])) === null) {
                continue;
            }

            if ($this->rbacManager->hasChild($currentItem, $childItem)) {
                $this->rbacManager->removeChild($currentItem, $childItem);
            }
        }

        return $this->asJson([
            'success' => true,
            'data' => [
                'assigned' => $this->getAssignedItems($currentItem),
                'unassigned' => $this->getUnassignedItems($currentItem),
            ],
        ]);
    }

    /**
     * @param string $name
     * @param string $type
     * @return Item|null
     */
    private function getItem(string $name, string $type): ?Item
    {
        $methodMap = [
            Item::TYPE_ROLE => 'getRole',
            Item::TYPE_PERMISSION => 'getPermission',
        ];

        $methodName = $methodMap[$type] ?? null;
        if ($methodName === null) {
            return null;
        }

        return $this->rbacManager->{$methodName}($name);
    }

    /**
     * @param Request $request
     * @return Item|null
     */
    private function getCurrentItem(Request $request): ?Item
    {
        [
            'name' => $name,
            'type' => $type,
        ] = $request->getQueryParams();

        return $this->getItem($name, $type);
    }

    /**
     * @param Item $currentItem
     * @return array
     */
    private function getAssignedItems(Item $currentItem): array
    {
        $roles = [
            'text' => 'Roles',
            'state' => [
                'expanded' => true,
            ],
            'children' => [],
        ];

        $permissions = [
            'text' => 'Permissions',
            'state' => [
                'expanded' => true,
            ],
            'children' => [],
        ];

        $items = $this->rbacManager->getChildren($currentItem->getName());

        foreach ($items as $item) {
            $children = [
                'text' => $item->getName(),
                'data' => [
                    'id' => $item->getName(),
                    'type' => $item->getType(),
                ],
            ];

            /* @var $children Item */
            if ($item->getType() === Item::TYPE_ROLE) {
                $roles['children'][] = $children;
            } else if ($item->getType() === Item::TYPE_PERMISSION) {
                $permissions['children'][] = $children;
            }
        }

        $data = array_filter(
            [$roles, $permissions],
            function (array $item) {
                return !empty($item['children']);
            }
        );

        return array_values($data);
    }

    /**
     * @param Item $currentItem
     * @return array
     */
    private function getUnassignedItems(Item $currentItem): array
    {
        $roles = [
            'text' => 'Roles',
            'state' => [
                'expanded' => true,
            ],
            'children' => [],
        ];

        $permissions = [
            'text' => 'Permissions',
            'state' => [
                'expanded' => true,
            ],
            'children' => [],
        ];

        $items = $this->rbacManager->getRoles() + $this->rbacManager->getPermissions();

        foreach ($items as $item) {
            if ($currentItem === $item
                || $this->rbacManager->hasChild($currentItem, $item)
                || !$this->rbacManager->canAddChild($currentItem, $item)) {
                continue;
            }

            $children = [
                'text' => $item->getName(),
                'data' => [
                    'id' => $item->getName(),
                    'type' => $item->getType(),
                ],
            ];

            /* @var $children Item */
            if ($item->getType() === Item::TYPE_ROLE) {
                $roles['children'][] = $children;
            } else if ($item->getType() === Item::TYPE_PERMISSION) {
                $permissions['children'][] = $children;
            }
        }

        $data = array_filter(
            [$roles, $permissions],
            function (array $item) {
                return !empty($item['children']);
            }
        );

        return array_values($data);
    }
}
