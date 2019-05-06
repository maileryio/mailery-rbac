<?php

namespace Mailery\Rbac\Commands;

use Mailery\Rbac\ManagerInterface as AuthManager;
use Mailery\User\ManagerInterface as UserManager;

class AssignController extends \Yiisoft\Yii\Console\Controller
{

    /**
     * @var AuthManager
     */
    private $authManager;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @param string $id
     * @param string $module
     * @param AuthManager $authManager
     * @param UserManager $userManager
     * @param array $config
     */
    public function __construct($id, $module, AuthManager $authManager, UserManager $userManager, $config = [])
    {
        $this->authManager = $authManager;
        $this->userManager = $userManager;
        parent::__construct($id, $module, $config);
    }

    /**
     * @param string $roleName
     * @param string $email
     * @return int
     */
    public function actionIndex(string $roleName, string $email)
    {
        $role = $this->authManager->getRole($roleName);

        foreach ($this->userManager->findAllByEmail($email) as $user) {
            $this->authManager->revoke($role, $user->getId());
            $this->authManager->assign($role, $user->getId());
        }

        return self::EXIT_CODE_NORMAL;
    }

}
