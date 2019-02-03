<?php

namespace mailery\rbac;

interface ManagerInterface extends \yii\rbac\ManagerInterface
{

    /**
     * Default role for new users
     * @return \yii\rbac\Role
     */
    public function getDefaultUserRole(): \yii\rbac\Role;

}
