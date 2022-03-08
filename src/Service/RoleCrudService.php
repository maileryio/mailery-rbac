<?php

namespace Mailery\Rbac\Service;

use Cycle\ORM\ORMInterface;
use Mailery\Rbac\ValueObject\RoleValueObject;
use Yiisoft\Rbac\Role;
use Yiisoft\Rbac\Manager as RbacManager;

class RoleCrudService
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
