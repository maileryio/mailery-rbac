<?php

namespace mailery\rbac;

/**
 * Suggest module
 * https://github.com/yii2mod/yii2-rbac
 */
class Module extends \yii\base\Module
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (\Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'mailery\rbac\commands';
        }

        parent::init();
    }

}
