<?php

use lakerLS\dynamicPage\models\Type;

/**
 * @var yii\web\View $this
 * @var Type $model
 * @var object $article
 */

$this->title = 'Редактировать: '.$model->name ;
$this->params['breadcrumbs'][] = ['label' => 'Типы содержимого', 'url' => '/dynamic-page/type/index'];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= $this->render('_form', [
        'model' => $model,
        'article' => $article,
    ]) ?>

