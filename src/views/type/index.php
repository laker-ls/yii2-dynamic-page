<?php

use lakerLS\dynamicPage\models\search\TypeSearch;
use yii\helpers\Html;
use yii\grid\GridView;
/**
 * @var yii\web\View $this
 * @var TypeSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Типы содержимого';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-body">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout'=>"{items}",

            'columns' => [
                ['attribute' => 'name',
                    'header' => 'Наименование'],

                ['attribute' => 'type',
                    'header' => 'Тип содержимого'],

                ['attribute' => 'category',
                    'header' => 'Категория',
                    'contentOptions' => ['width' => '1', 'style' => 'text-align:center'],
                ],

                ['attribute' => 'article',
                    'header' => 'Статья',
                    'contentOptions' => ['width' => '1', 'style' => 'text-align:center'],
                ],

                ['attribute' => 'nested',
                    'header' => 'Вложенность',
                    'contentOptions' => ['width' => '1', 'style' => 'text-align:center'],
                ],

                ['class' => 'yii\grid\ActionColumn',
                    'header' => 'Действия',
                    'headerOptions' => ['style' => 'text-align: center', 'width' => '105'] ,
                    'contentOptions' => ['style' => 'text-align: center;'],
//                    'urlCreator'=>function($action, $model, $key, $index){
//                        return ['content/'. $action, 'id' => $model->id];
//
//                    },
                    'template' => '{moveUp} {moveDown} {update} {delete} ',
                    'buttons' => [
                        'moveUp' => function($url, $model) {     // render your custom button
                            return Html::a("<i class='glyphicon glyphicon-triangle-top'></i>", ["/dynamic-page/type/move-up", "id" => $model->id], ['data-id' => $model->id, 'class' => 'move-jquery']);
                        },
                        'moveDown' => function($url, $model) {     // render your custom button
                            return Html::a("<i class='glyphicon glyphicon-triangle-bottom'></i>", ["/dynamic-page/type/move-down", "id" => $model->id], ['data-id' => $model->id, 'class' => 'move-jquery']);
                        },
                    ]
                ],
            ],
        ]); ?>

        <div>
            <?= Html::a('Добавить тип содержимого', ['type/create'], ['class' => 'btn btn-success']) ?>
        </div>

    </div>
</div>
