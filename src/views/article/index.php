<?php

use yii\helpers\Html;

/**
 * @var object $dataProvider
 * @var object $searchModel
 */

$this->title = 'Статьи';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,

    'columns' => [
        'category_id',
        'name',
        'url',
        ['class' => 'yii\grid\ActionColumn',
            'header' => 'Действия',
            'template' => '{update} {delete}',

            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', "{$url}&type={$model->type}");
                },
                'delete' => function ($url) {
                    $currentUrl = Yii::$app->request->pathInfo;
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', "{$url}&redirect={$currentUrl}", [
                        'title' => 'Удалить',
                        'aria-label' => 'Удалить',
                        'data-pjax' => 0,
                        'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                        'data-method' => 'post',
                    ]);
                }
            ]
        ]
    ],
]);

echo Html::beginTag('div');
echo Html::a('Добавить статью', ['article/create'], ['class' => 'btn btn-success']);
echo Html::endTag('div');
