<?php

namespace mailery\rbac\components;

class DbManager extends \yii\rbac\DbManager implements \mailery\rbac\ManagerInterface
{

    /**
     * @inheritdoc
     */
    public function getAdminUserRole(): \yii\rbac\Role
    {
        return $this->getRole('admin');
    }

    /**
     * @todo move to setting via user interface or config file
     * @inheritdoc
     */
    public function getDefaultUserRole(): \yii\rbac\Role
    {
        return $this->getRole('admin');
    }

}
