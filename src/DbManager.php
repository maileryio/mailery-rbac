<?php

namespace Mailery\Rbac;

class DbManager extends \Yiisoft\Rbac\DbManager implements \Mailery\Rbac\ManagerInterface
{

    /**
     * @inheritdoc
     */
    public function getAdminUserRole(): \Yiisoft\Rbac\Role
    {
        return $this->getRole('admin');
    }

    /**
     * @todo move to setting via user interface or config file
     * @inheritdoc
     */
    public function getDefaultUserRole(): \Yiisoft\Rbac\Role
    {
        return $this->getRole('admin');
    }

}
