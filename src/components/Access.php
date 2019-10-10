<?php

namespace lakerLS\dynamicPage\components;

use Yii;

class Access
{
    /**
     * Роль должна быть массивом, если приходит строка, конвертируем в массив, в остальных случаях ошибка.
     * По умолчанию доступ открыт для роли `admin`.
     *
     * @return mixed
     * @throws Exception
     */
    public static function getRoles()
    {
        if (isset(Yii::$app->getModule('dynamic-page')->params['accessRoles'])) {
            $roles = Yii::$app->getModule('dynamic-page')->params['accessRoles'];
            if (is_array($roles)) {
                return $roles;
            } elseif (is_string($roles) && strrpos($roles, ',') === false) {
                return [$roles];
            } else {
                throw new Exception('Передаваемый параметр "accessRoles" должен быть массивом или строкой без запятых.');
            }
        } else {
            return ['admin'];
        }
    }

    /**
     * Проверка доступа роли, для Yii::$app->user->can().
     * @return boolean
     */
    public static function checkPermission()
    {
        $roles = self::getRoles();
        if (is_string($roles)) {
            $roles = [$roles];
        }

        foreach ($roles as $role) {
            if (Yii::$app->user->can($role)) {
                return true;
            }
        }
        return false;
    }
}