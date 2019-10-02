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
        if (Yii::$app->user->can('admin')) {
            echo Html::button('Создать статью', [
                'class' => 'create-article',
                'data-category-id' => $categoryId,
                'data-type' => $type,
            ]);
        }
    }

    /**
     * Редактирование/удаление статьи.
     * @param integer $articleId
     */
    public static function change($articleId)
    {
        if (Yii::$app->user->can('admin')) {
            $currentUrl = Yii::$app->request->pathInfo;

            echo Html::a('✎', '#', [
                'class' => 'update-article',
                'data-article-id' => $articleId,
                'title' => 'Редактировать',
            ]);

            echo Html::beginForm("/dynamic-page/article/delete?id={$articleId}&redirect={$currentUrl}", 'post', [
                'class' => 'delete-article-form',
            ]);
                echo Html::submitButton('✘', [
                    'class' => 'delete-article',
                    'data-method' => 'post',
                    'data-confirm-delete' => true,
                    'title' => 'Удалить',
                ]);
            echo Html::endForm();
        }
    }
}