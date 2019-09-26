<?php

use yii\helpers\Html;

$this->title = 'Ошибка при удалении';
$this->params['breadcrumbs'][] = ['label' => 'Типы содержимого', 'url' => '/dynamic-page/type/index'];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="error-type">
    <h1>Ошибка при удалении "Типа содержимого".</h1>



    <?php if (!empty($categories) || !empty($articles)) : ?>
        <?php if (!empty($categories)) : ?>
            <h4>Некоторые категории используют данный "Тип содержимого" Необходимо переназначить типы у следующих категорий:</h4>
            <div class="list">
                <?php foreach ($categories as $key => $category) : ?>
                    <?php $url = "/dynamic-page/category/index"; ?>
                    <p>
                        id - <b><?= Html::a($category->id, $url, ['target' => '_blank']) ?></b>,
                        наименование - <b><?= Html::a($category->name, $url, ['target' => '_blank']) ?></b>
                    </p>
                    <?php if ($key + 1 < count($categories)) : ?>
                        <hr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($articles)) : ?>
            <h4>Некоторые статьи используют данный "Тип содержимого" Необходимо переназначить типы у следующих статей:</h4>
            <div class="list">
                <?php foreach ($articles as $key => $article) : ?>
                    <?php $url = "/dynamic-page/article/update?id={$article->id}&type={$article->type}"; ?>
                    <p>
                        id - <b><?= Html::a($article->id, $url, ['target' => '_blank']) ?></b>,
                        наименование - <b><?= Html::a($article->name, $url, ['target' => '_blank']) ?></b></p>
                    <?php if ($key + 1 < count($articles)) : ?>
                        <hr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="buttons">
        <?php
        echo Html::a('Назад', '/dynamic-page/type/index', ['class' => 'back']);
        echo Html::a('Повторить удаление', '', ['class' => 'delete', 'data-method' => 'post']);
        ?>
    </div>
</section>
