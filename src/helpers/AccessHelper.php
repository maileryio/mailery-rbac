<?php

namespace notty\rbac\helpers;

use Yii;
use yii\web\User;

class AccessHelper
{

    /**
     * Check access route for rbac.
     * @param string|array $route
     * @param array $params
     * @param integer|User $user
     * @return boolean
     */
    public static function checkRoute($route, $params = [], User $user = null)
    {
        $r = static::normalizeRoute($route);
        if ($user->can($r, $params)) {
            return true;
        }
        while (($pos = strrpos($r, '/')) > 0) {
            $r = substr($r, 0, $pos);
            if ($user->can($r . '/*', $params)) {
                return true;
            }
        }
        return $user->can('/*', $params);
    }

    /**
     * Normalize route
     *
     * @param string $route Plain route string
     * @return string Normalized route string
     */
    protected static function normalizeRoute($route)
    {
        if ($route === '') {
            $normalized = '/' . Yii::$app->controller->getRoute();
        } elseif (strncmp($route, '/', 1) === 0) {
            $normalized = $route;
        } elseif (strpos($route, '/') === false) {
            $normalized = '/' . Yii::$app->controller->getUniqueId() . '/' . $route;
        } elseif (($mid = Yii::$app->controller->module->getUniqueId()) !== '') {
            $normalized = '/' . $mid . '/' . $route;
        } else {
            $normalized = '/' . $route;
        }
        return $normalized;
    }

}
