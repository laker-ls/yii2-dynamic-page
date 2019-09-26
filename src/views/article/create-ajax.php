<?php

use lakerLS\dynamicPage\models\Article;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * @var array $typeField
 * @var Article $model
 * @var integer $categoryId
 * @var object $type
 * @var string $currentUrl
 * @var array $typeDropDown
 */
?>

<div id="content" class="padding-20">
    <div class="panel panel-default">
        <div class="panel-body form-input ">
            <?php
            $form = ActiveForm::begin(['action' => "/dynamic-page/article/create?redirect={$currentUrl}", 'enableAjaxValidation' => true]);

            echo $form->field($model, 'category_id')->hiddenInput(['value' => $categoryId])->label(false);

            if (!empty($type->id)) {
                echo $form->field($model, 'type')->hiddenInput(['value' => $type->type])->label(false);
            } else {
                echo $form->field($model, 'type')->dropDownList(
                    ArrayHelper::map($typeDropDown, 'type', 'name'), [
                        'prompt' => '--------------------',
                    ]
                )->label($model->attributeLabels()['type'] . ' <span style="color: red">*</span>');
            }

            if (!empty(Yii::$app->request->get('type'))) {
                echo $this->render('_form',[
                    'typeField' => $typeField,
                    'form' => $form,
                    'model' => $model,
                ]);
            }

            ActiveForm::end();
            ?>
        </div>
    </div>
</div>