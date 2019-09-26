<?php

namespace lakerLS\dynamicPage\helpers;

use yii\base\Model;

class FormHelper
{
    /**
     * Добавляем к наименованию звезду, обозначающую, что поле обязательно к заполнению.
     *
     * @param Model $model
     * @param string $name
     * @return string
     */
    public static function required(Model $model, $name)
    {
        return $model->attributeLabels()[$name] . ' <span style="color: red">*</span>';
    }
}