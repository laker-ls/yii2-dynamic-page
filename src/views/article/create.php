<?php

use lakerLS\dynamicPage\helpers\ArticleHelper;
use lakerLS\dynamicPage\models\Article;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * @var array $typeField
 * @var Article $model
 * @var array $typeDropDown
 */

$this->title = 'Добавить запись';
$mainUrl = '/dynamic-page/article/index';
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => $mainUrl];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<div id="content" class="padding-20">
    <div class="panel panel-default">
        <div class="panel-body form-input ">
            <?php
            $form = ActiveForm::begin(['action' => "create?redirect={$mainUrl}", 'enableAjaxValidation' => true]);

            echo $form->field($model, 'category_id')->dropDownList(
                ArrayHelper::map(ArticleHelper::categoryDropDown(), 'id', 'name'), [
                    'prompt' => '--------------------',
                    'options' => [Yii::$app->request->get('category_id') => ['Selected' => true]],
                    'class' => 'form-control category-id',
                ]
            )->label($model->attributeLabels()['category_id'] . ' <span style="color: red">*</span>');

            if (!empty(Yii::$app->request->get('category_id'))) {
                echo $form->field($model, 'type')->dropDownList(
                    ArrayHelper::map($typeDropDown, 'type', 'name'), [
                        'prompt' => '--------------------',
                        'options' => [ArrayHelper::getValue(Yii::$app->request->get(), 'type') => ['Selected' => true]],
                        'class' => 'form-control category-type'
                    ]
                )->label($model->attributeLabels()['type'] . ' <span style="color: red">*</span>');

                if (!empty(Yii::$app->request->get('type'))) {
                    echo $this->render('overriding/_form',[
                        'typeField' => $typeField,
                        'form' => $form,
                        'model' => $model,
                    ]);
                }
            }

            ActiveForm::end();
            ?>
        </div>
    </div>
</div>