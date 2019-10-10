<?php

namespace lakerLS\dynamicPage\widgets;

use lakerLS\dynamicPage\components\Access;
use Yii;
use yii\helpers\Html;

/**
 * Виджеты предоставляют возможность управления статьями во фронтенде с помощью ajax, т.е. без перехода в админ-панель.
 */
class Crud
{
    /**
     * Создание статьи в текущей категории.
     * @param integer $categoryId
     * @param string $type
     */
    public static function create($categoryId, $type)
    {
        if (Access::checkPermission()) {
            echo Html::button('Создать статью', [
                'class' => 'create-article',
                'data-category-id' => $categoryId,
                'data-type' => $type,
            ]);
        }
    }

    /**
     * Редактирование/удаление статьи, а так же перемещение записей (смена позиции) относительно друг друга.
     * @param integer $articleId
     */
    public static function change($articleId)
    {
        if (Access::checkPermission()) {
            $currentUrl = Yii::$app->request->pathInfo;

            echo Html::a('▲', "/dynamic-page/article/move-up?id={$articleId}", [
                'class' => 'move-article',
                'data-id' => $articleId,
            ]);
            echo Html::a('▼', "/dynamic-page/article/move-down?id={$articleId}", [
                'class' => 'move-article',
                'data-id' => $articleId,
            ]);
            echo Html::a('✎', '#', [
                'class' => 'update-article',
                'data-article-id' => $articleId,
                'title' => 'Редактировать',
            ]);
            echo Html::a('✘', "/dynamic-page/article/delete?id={$articleId}&redirect={$currentUrl}",[
                'class' => 'delete-article',
                'data-method' => 'post',
                'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'title' => 'Удалить',
            ]);
        }
    }
}