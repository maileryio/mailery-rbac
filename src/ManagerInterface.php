<?php

namespace Mailery\Rbac;

interface ManagerInterface extends \Yiisoft\Rbac\ManagerInterface
{

    /**
     * Admin role
     * @return \Yiisoft\Rbac\Role
     */
    public function getAdminUserRole(): \Yiisoft\Rbac\Role;

    /**
     * Default role for new users
     * @return \Yiisoft\Rbac\Role
     */
    public function getDefaultUserRole(): \Yiisoft\Rbac\Role;

}
