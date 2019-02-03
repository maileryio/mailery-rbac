<?php

namespace mailery\rbac\components;

class DbManager extends \yii\rbac\DbManager implements \mailery\rbac\ManagerInterface
{

    /**
     * @todo move to setting via user interface or config file
     * @inheritdoc
     */
    public function getDefaultUserRole(): \yii\rbac\Role
    {
        return $this->getRole('admin');
    }

}
