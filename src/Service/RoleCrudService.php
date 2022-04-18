<?php

namespace Mailery\Rbac\Service;

use Mailery\Rbac\ValueObject\RoleValueObject;
use Yiisoft\Rbac\Role;
use Yiisoft\Rbac\Manager as RbacManager;

class RoleCrudService
{
    /**
     * @param RbacManager $rbacManager
     */
    public function __construct(
        private RbacManager $rbacManager
    ) {}

    /**
     * @param RoleValueObject $valueObject
     * @return Role
     */
    public function create(RoleValueObject $valueObject): Role
    {
        $role = (new Role($valueObject->getName()))
            ->withRuleName($valueObject->getRuleName())
            ->withDescription($valueObject->getDescription())
            ->withCreatedAt(time())
            ->withUpdatedAt(time());

        $this->rbacManager->addRole($role);

        return $role;
    }

    /**
     * @param Role $role
     * @param RoleValueObject $valueObject
     * @return Template
     */
    public function update(Role $role, RoleValueObject $valueObject): Role
    {
        $name = $role->getName();

        $role = $role
            ->withName($valueObject->getName())
            ->withRuleName($valueObject->getRuleName())
            ->withDescription($valueObject->getDescription())
            ->withUpdatedAt(time());

        $this->rbacManager->updateRole($name, $role);

        return $role;
    }

    /**
     * @param Role $role
     * @return bool
     */
    public function delete(Role $role): bool
    {
        $this->rbacManager->removeRole($role);

        return true;
    }
}
