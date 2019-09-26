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
     * Актуальная модель. Может быть переопределена.
     * @var object $modelMap
     */
    public $modelMap = [];

    public $bootstrapVersion = 3;

    /**
     * Подключение Asset.
     */
    public function init()
    {
        parent::init();
        DynamicPageAsset::register(Yii::$app->view);
    }
}