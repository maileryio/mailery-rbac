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

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Rbac\Item;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface;
use Yiisoft\Rbac\Manager as RbacManager;
use Yiisoft\Rbac\StorageInterface as RbacStorage;
use Yiisoft\Yii\Api\ResponseFactory\JsonResponseFactory;

class AssignController
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
     * @var RbacManager
     */
    private RbacManager $rbacManager;

    /**
     * @var RbacStorage
     */
    private RbacStorage $rbacStorage;

    /**
     * @param ViewRenderer $viewRenderer
     * @param ResponseFactoryInterface $responseFactory
     * @param JsonResponseFactory $jsonResponseFactory
     * @param RbacManager $rbacManager
     * @param RbacStorage $rbacStorage
     */
    public function __construct(
        ViewRenderer $viewRenderer,
        ResponseFactoryInterface $responseFactory,
        JsonResponseFactory $jsonResponseFactory,
        RbacManager $rbacManager,
        RbacStorage $rbacStorage
    ) {
        $this->viewRenderer = $viewRenderer
            ->withController($this)
            ->withViewBasePath(dirname(dirname(__DIR__)) . '/views');

        $this->responseFactory = $responseFactory;
        $this->jsonResponseFactory = $jsonResponseFactory;
        $this->rbacManager = $rbacManager;
        $this->rbacStorage = $rbacStorage;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function assigned(Request $request): Response
    {
        if (($currentItem = $this->getCurrentItem($request)) === null) {
            return $this->responseFactory
                ->createResponse(404);
        }

        return $this->jsonResponseFactory
            ->createResponse($this->getAssignedItems($currentItem));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function unassigned(Request $request): Response
    {
        if (($currentItem = $this->getCurrentItem($request)) === null) {
            return $this->responseFactory
                ->createResponse(404);
        }

        return $this->jsonResponseFactory
            ->createResponse($this->getUnassignedItems($currentItem));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function assign(Request $request): Response
    {
        if (($currentItem = $this->getCurrentItem($request)) === null) {
            return $this->responseFactory
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

        return $this->jsonResponseFactory
            ->createResponse([
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
            return $this->responseFactory
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

        return $this->jsonResponseFactory
            ->createResponse([
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
            Item::TYPE_ROLE => 'getRoleByName',
            Item::TYPE_PERMISSION => 'getPermissionByName',
        ];

        $methodName = $methodMap[$type] ?? null;
        if ($methodName === null) {
            return null;
        }

        return $this->rbacStorage->{$methodName}($name);
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

        $items = $this->rbacStorage->getChildrenByName($currentItem->getName());

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
            } else {
                if ($item->getType() === Item::TYPE_PERMISSION) {
                    $permissions['children'][] = $children;
                }
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

        $items = $this->rbacStorage->getRoles() + $this->rbacStorage->getPermissions();

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
            } else {
                if ($item->getType() === Item::TYPE_PERMISSION) {
                    $permissions['children'][] = $children;
                }
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
