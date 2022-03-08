<?php

namespace Mailery\Rbac\Service;

use Cycle\ORM\ORMInterface;
use Mailery\Rbac\ValueObject\PermissionValueObject;
use Yiisoft\Rbac\Permission;
use Yiisoft\Rbac\Manager as RbacManager;

class PermissionCrudService
{
    /**
     * @var ORMInterface
     */
    private ORMInterface $orm;

    /**
     * @var RbacManager
     */
    private RbacManager $rbacManager;

    /**
     * @param ORMInterface $orm
     * @param RbacManager $rbacManager
     */
    public function __construct(
        ORMInterface $orm,
        RbacManager $rbacManager
    ) {
        $this->orm = $orm;
        $this->rbacManager = $rbacManager;
    }

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
