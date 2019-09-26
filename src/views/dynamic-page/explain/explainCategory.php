<?php

/**
 * @var $this ->context->allCategories array, который содержит список всех категорий.
 * @var $category object, который содержит текущую категорию.
 */
use lakerLS\dynamicPage\widgets\Crud;
use yii\widgets\ListView;

Crud::create($category->id, $category->type);
?>

    <h1 style="text-align: center">Это представление для статей</h1>

<?php
echo ListView::widget([
    'dataProvider' => $dataProvider,

    'layout' => "{items}",

    'itemOptions' => [
        'tag' => false,
    ],
    'options' => [
        'tag' => 'article',
        'class' => 'post ajax-area',
    ],
    'itemView' => '_explain',

    'viewParams' => [
        'category' => $category,
        'allCategories' => $this->context->allCategories,
    ],
]);
?>