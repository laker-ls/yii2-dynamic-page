<?php

use lakerLS\dynamicPage\models\Article;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * В данном файле указываются пресеты, которые используются для отображения полей.
 * Пресеты задают различный внешний вид полям.
 *
 * @var ActiveForm $form
 * @var Article $model
 * @var array $typeField
 */

echo $form->field($model, 'author_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false);

if (!empty($typeField[0])) {
    foreach ($typeField as $field) {
        $type = Yii::$app->request->get('type');
        $view = "_{$type}Preset";

        if (file_exists(__DIR__ . '/_presets/' . $view . '.php') === false) {
            $view = '_defaultPreset';
        }

        echo $this->render('_presets/' . $view, [
            'form' => $form,
            'model' => $model,
            'field' => $field,
        ]);
    }
}

echo Html::beginTag('div', ['class' => 'form-group']);
    echo Html::tag('input', $model->isNewRecord ? 'Создать' : 'Изменить', [
        'type' => 'submit',
        'class' => 'btn btn-success',
    ]);
echo Html::endTag('div');