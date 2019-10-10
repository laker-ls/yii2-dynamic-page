<?php

use kartik\tree\TreeView;
use kartik\tree\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var \lakerLS\dynamicPage\models\Category $anyCategory */

$this->title = 'Меню и содержимое';
$this->params['breadcrumbs'][] = 'Наполнение сайта';
$this->params['breadcrumbs'][] = $this->title;
?>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">

<div class="panel panel-default">
    <div class="panel-heading">
        <span class="title elipsis">
            <strong>Категории</strong>
        </span>
    </div>
    <div class="panel-body">
        <?php
        /** @var array $treeView */
        /** @var array $treeCheck */
        echo TreeView::widget([
            'query' => $treeView,
            'nodeActions' => [
                'move' => Url::to(['/dynamic-page/category/move']),
            ],

            'options' => ['id' => 'myTree'],

            // Переназначем классы иконок на существующие.
            'fontAwesome' => true,
            'defaultExpandNodeIcon' => '<i class="far fa-plus-square"></i>',
            'defaultCollapseNodeIcon' => '<i class="far fa-minus-square"></i>',
            'defaultParentNodeOpenIcon' => '<i class="fas fa-folder-open kv-node-opened"></i>',
            'defaultParentNodeIcon' => '<i class="fas fa-folder kv-node-closed"></i>',

            'topRootAsHeading' => true,
            'detailOptions' => ['style' => 'background-color:#f6f8f8;'],
            'treeOptions' => ['style' => 'height:437px;'],
            'mainTemplate' =>
                '<div class="row darkColor">
                                <div class="col-sm-5">
                                    {wrapper}
                                </div>
                                <div class="col-sm-7">
                                    {detail}
                                </div>
                            </div>',

            'headerTemplate' =>
                '<div class="row">
                                <div class="col-sm-12">
                                    {search}
                                </div>
                            </div>',


            'headingOptions' => ['label' => false],
            'allowNewRoots' => $treeCheck,
            'isAdmin' => $treeCheck,
            'iconEditSettings' => ['show' => 'none'],
            'displayValue' => ArrayHelper::getValue($anyCategory, 'root'),
            'softDelete' => false,
            'cacheSettings' => [
                'enableCache' => false
            ],

            'showIDAttribute' => false,
            'showNameAttribute' => false,

            'nodeAddlViews' => [
                Module::VIEW_PART_2 => '@lakerLS/dynamicPage/views/category/_form_2',
            ]
        ]);

        ?>
    </div>
</div>