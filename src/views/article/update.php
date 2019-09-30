<?php

use lakerLS\dynamicPage\helpers\ArticleHelper;
use lakerLS\dynamicPage\models\Article;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model Article
 * @var array $typeField
 * @var array $categoryDropDown
 * @var array $typeDropDown
 */

$this->title = 'Редактировать запись: ' . $model->name;
$mainPage = '/dynamic-page/article/index';
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => $mainPage];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div id="content" class="padding-20">
    <div class="panel panel-default">
        <div class="panel-body form-input ">
            <?php
            $form = ActiveForm::begin(['action' => "update?id={$model->id}&redirect={$mainPage}", 'enableAjaxValidation' => true]);

            echo $form->field($model, 'category_id')->dropDownList(
                ArrayHelper::map(ArticleHelper::categoryDropDown(), 'id', 'name')
            )->label($model->attributeLabels()['category_id'] . ' <span style="color: red">*</span>');

            echo $form->field($model, 'type')->dropDownList(
                ArrayHelper::map($typeDropDown, 'type', 'name')
            )->label($model->attributeLabels()['type'] . ' <span style="color: red">*</span>');

            echo $this->render('overriding/_form', [
                'typeField' => $typeField,
                'form' => $form,
                'model' => $model,
            ]);

            ActiveForm::end();
            ?>
        </div>
    </div>
</div>