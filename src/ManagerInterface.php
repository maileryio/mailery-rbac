<?php

namespace mailery\rbac;

interface ManagerInterface extends \yii\rbac\ManagerInterface
{

    /**
     * Admin role
     * @return \yii\rbac\Role
     */
    public function getAdminUserRole(): \yii\rbac\Role;

    /**
     * Default role for new users
     * @return \yii\rbac\Role
     */
    public function getDefaultUserRole(): \yii\rbac\Role;

}
