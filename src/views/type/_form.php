<?php

use kartik\select2\Select2;
use lakerLS\dynamicPage\helpers\FormHelper;
use lakerLS\dynamicPage\models\Article;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model lakerLS\dynamicPage\models\Type */
/* @var $form yii\widgets\ActiveForm */
/* @var array $array */
/* @var Article $article */

?>

<div class="panel panel-default">
    <div class="panel-body form-input ">
        <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'name')->textInput()->label(FormHelper::required($model, 'name')) ?>
            <?= $form->field($model, 'type')->textInput()->label(FormHelper::required($model, 'type')) ?>

            <?= $form->field($model, 'category')->checkbox(['class' => 'category-checkbox']) ?>
            <div class="for-category" style="display: none">
                <?= $form->field($model, 'nested')->checkbox() ?>
            </div>
            <?= $form->field($model, 'article')->checkbox(['class' => 'article-checkbox']) ?>

            <div class="for-article" style="display: none">
                <?php
                foreach (explode(',', $model->select) as $value) {
                    $array[$value] = $value;
                }
                $model->select = $array;

                $data = $article->attributeLabels();
                unset($data['category_id']);
                unset($data['type']);

                echo $form->field($model, 'select')->widget(Select2::class, [
                    'data' => $data,
                    'options' => ['multiple' => true, 'placeholder' => 'Выбрать ...'],
                    'pluginOptions' => ['allowClear' => true],
                ]);
                ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
