<?php

namespace notty\rbac;

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
            $this->controllerNamespace = 'notty\rbac\commands';
        }

        parent::init();
    }

}
