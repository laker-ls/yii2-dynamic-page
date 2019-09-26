<?php

namespace lakerLS\dynamicPage;
use Yii;

/**
 * Модуль расширения categoryArticle.
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'lakerLS\dynamicPage\controllers';

    /**
     * Массив актуальных путей моделей. Через конфиг переопределяются стандартные пути к моделям.
     * @var array $modelMap
     */
    public $modelMap = [];

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

    /**
     * Инициализация модуля.
     */
    public function init()
    {
        parent::init();
        DynamicPageAsset::register(Yii::$app->view);
        $this->modelMap();
    }

    /**
     * Переопределение моделей, если в конфиге переданы переопределенные пути.
     */
    private function modelMap()
    {
        $this->_modelMap = array_merge($this->_modelMap, $this->modelMap);
        foreach ($this->_modelMap as $name => $definition) {
            $class = "lakerLS\\dynamicPage\\models\\" . $name;
            Yii::$container->set($class, $definition);
            $modelName = is_array($definition) ? $definition['class'] : $definition;
            $this->modelMap[$name] = $modelName;
        }
    }
}