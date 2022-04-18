<?php

namespace Mailery\Rbac\Service;

use Mailery\Rbac\ValueObject\PermissionValueObject;
use Yiisoft\Rbac\Permission;
use Yiisoft\Rbac\Manager as RbacManager;

class PermissionCrudService
{
    /**
     * @param RbacManager $rbacManager
     */
    public function __construct(
        private RbacManager $rbacManager
    ) {}

    /**
     * @param PermissionValueObject $valueObject
     * @return Permission
     */
    public function create(PermissionValueObject $valueObject): Permission
    {
        $permission = (new Permission($valueObject->getName()))
            ->withRuleName($valueObject->getRuleName())
            ->withDescription($valueObject->getDescription())
            ->withCreatedAt(time())
            ->withUpdatedAt(time());

        $this->rbacManager->addPermission($permission);

        return $permission;
    }

    /**
     * @param Permission $permission
     * @param PermissionValueObject $valueObject
     * @return Template
     */
    public function update(Permission $permission, PermissionValueObject $valueObject): Permission
    {
        $name = $permission->getName();

        $permission = $permission
            ->withName($valueObject->getName())
            ->withRuleName($valueObject->getRuleName())
            ->withDescription($valueObject->getDescription())
            ->withUpdatedAt(time());

        $this->rbacManager->updatePermission($name, $permission);

        return $permission;
    }

    /**
     * @param Permission $permission
     * @return bool
     */
    public function delete(Permission $permission): bool
    {
        $this->rbacManager->removePermission($permission);

        return true;
    }
}
