<?php

namespace lakerLS\dynamicPage;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DynamicPageAsset extends AssetBundle
{
    public $sourcePath = '@lakerLS/dynamicPage/assets';
    public $css = [
        'css/dynamic-page.css',
    ];
    public $js = [
        'js/dynamic-page.js',
    ];

    public function init()
    {
        if (ArrayHelper::getValue(Yii::$app->params, 'bootstrapVersion') == 4) {
            $this->depends = [
                'yii\bootstrap4\BootstrapAsset',
                'yii\bootstrap4\BootstrapPluginAsset',
            ];
        } else {
            $this->depends = [
                'yii\bootstrap\BootstrapAsset',
                'yii\bootstrap\BootstrapPluginAsset',
            ];
        }
        parent::init();
    }
}