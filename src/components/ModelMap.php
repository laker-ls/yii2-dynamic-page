<?php

namespace lakerLS\dynamicPage\components;

use lakerLS\dynamicPage\Module;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * Удобное получение данных из модели, которая может быть переопределена.
 */
class ModelMap extends ActiveRecord
{
    /**
     * Поиск в указанной модели.
     * ПРИМЕР: ModelMap::findName('Article')->one();
     *
     * @param string $model имя модели.
     * @return object|ActiveQuery the newly created [[ActiveQuery]] instance.
     */
    public static function findByName($model)
    {
        if (empty(Module::getInstance()->modelMap)) {
            Yii::$app->getModule('dynamic-page')->init();
        }

        $fullPath = Module::getInstance()->modelMap[$model];
        return Yii::createObject(ActiveQuery::class, [$fullPath]);
    }

    /**
     * Получение экземляра модели.
     *
     * @param string $model имя модели.
     * @return object
     */
    public static function new($model)
    {
        $fullPath = Module::getInstance()->modelMap[$model];
        return new $fullPath;
    }

    /**
     * Использование данного метода не является корректным через данный класс.
     *
     * @throws Exception
     */
    public static function find()
    {
        throw new Exception('Используйте "findName" для получения данных.');
    }
}