<?php

use lakerLS\dynamicPage\models\Article;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model Article
 * @var array $typeField
 * @var object $type
 * @var string $currentUrl
 */
?>

<div id="content" class="padding-20">
    <div class="panel panel-default">
        <div class="panel-body form-input ">
            <?php
            $form = ActiveForm::begin(['action' => "/dynamic-page/article/update?id={$model->id}&redirect={$currentUrl}", 'enableAjaxValidation' => true]);

            echo $form->field($model, 'category_id')->hiddenInput(['value' => $model->category_id])->label(false);

            echo $form->field($model, 'type')->hiddenInput(['value' => $model->type])->label(false);

            echo $this->render('_form', [
                'typeField' => $typeField,
                'form' => $form,
                'model' => $model,
            ]);

            ActiveForm::end();
            ?>
        </div>
    </div>
</div>