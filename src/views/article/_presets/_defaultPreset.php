<?php

use lakerLS\dynamicPage\helpers\FormHelper;
use yii\widgets\ActiveForm;

/**
 * Настраиваем необходимые инпуты.
 *
 * При необходимости отображать одни и тежи поля по разному для разных типов статей, необходимо создать новый пресет,
 * где в имени вместо `default` указываем тип статьи.
 *
 * Если у статьи указан тип 'blog' то файл пресета будет иметь следующее имя: _blogPreset.php
 *
 * ВАЖНО: Все пресеты создаются в папке `_presets`.
 *
 * @var $form ActiveForm
 * @var $model \lakerLS\dynamicPage\models\Article
 * @var $field string
 */

switch ($field) {
    case 'name':
        echo $form->field($model, $field)->textInput(['maxlength' => true, 'class' => 'form-control translate-of'])
            ->label(FormHelper::required($model, $field));
        break;
    case 'url':
        echo $form->field($model, $field)->textInput(['class' => 'form-control translate-in'])
            ->label(FormHelper::required($model, $field));
        break;
    case 'image':
        echo $form->field($model, $field)->textarea();
        break;
    case 'text':
        echo $form->field($model, $field)->textarea();
        break;
    case 'description':
        echo $form->field($model, $field)->textarea(['maxlength' => true, 'style' => 'height: 70px;']);
        break;
    default:
        echo $form->field($model, $field)->textInput();
}