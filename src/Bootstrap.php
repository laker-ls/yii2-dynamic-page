<?php

namespace lakerLS\dynamicPage;

use Yii;
use yii\base\BootstrapInterface;

/**
 * Bootstrap класс регистрирует модуль и компоненты приложения.
 * Реализуется возможность переопределения модели.
 */
class Bootstrap implements BootstrapInterface
{
    /** 
     * Массив стандартных путей моделей.
     * @var array $_modelMap
     */
    private $_modelMap = [
        'Article'  => 'lakerLS\dynamicPage\models\Article',
        'Category' => 'lakerLS\dynamicPage\models\Category',
        'Redirect' => 'lakerLS\dynamicPage\models\Redirect',
        'Type'     => 'lakerLS\dynamicPage\models\Type',
    ];

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var Module $module */
        /** @var \yii\db\ActiveRecord $modelName */
        if ($app->hasModule('dynamic-page') && ($module = $app->getModule('dynamic-page')) instanceof Module) {
            $this->_modelMap = array_merge($this->_modelMap, $module->modelMap);
            foreach ($this->_modelMap as $name => $definition) {
                $class = "lakerLS\\dynamicPage\\models\\" . $name;
                Yii::$container->set($class, $definition);
                $modelName = is_array($definition) ? $definition['class'] : $definition;
                $module->modelMap[$name] = $modelName;
            }
        }
    }
}