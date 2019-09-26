<?php

namespace lakerLS\dynamicPage\widgets;

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
        echo Html::button('Создать статью', [
            'class' => 'create-article',
            'data-category-id' => $categoryId,
            'data-type' => $type,
        ]);
    }

    /**
     * Редактирование/удаление статьи.
     * @param $articleId
     */
    public static function change($articleId)
    {
        $currentUrl = Yii::$app->request->pathInfo;

        echo Html::a('✎', '#', [
            'class' => 'update-article',
            'data-article-id' => $articleId,
        ]);
        echo Html::a('✘', "/dynamic-page/article/delete?id={$articleId}&redirect={$currentUrl}", [
            'class' => 'delete-article',
            'data-method' => 'post',
            'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?',
        ]);
    }
}