<?php
use lakerLS\dynamicPage\components\DynamicUrl;
use lakerLS\dynamicPage\widgets\Crud;

/** @var \lakerLS\dynamicPage\models\Article $model */

?>

<h3 style="text-align: center"><?= $model->name; Crud::change($model->id) ?></h3>
<div style="text-align: center">
    <a href="<?= DynamicUrl::full($this->context->allCategories, $model) ?>">Подробнее</a>
</div>

<hr>;