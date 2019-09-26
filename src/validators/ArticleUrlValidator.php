<?php

namespace lakerLS\dynamicPage\validators;

use lakerLS\dynamicPage\components\ModelMap;
use lakerLS\dynamicPage\models\Article;
use lakerLS\dynamicPage\models\Category;
use yii\helpers\ArrayHelper;
use yii\validators\Validator;

class ArticleUrlValidator extends Validator
{
    /**
     * Проверка статьи на уникальность в текущей вложенности. Совпадения на других уровнях вложенности не критичны.
     *
     * @param Article $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        /** @var Category $parent */
        $parent = ModelMap::findByName('Category')->where(['id' => $model->category_id])->one();

        $leavesCategory = $parent->children(1)->all();
        $leavesArticle = ModelMap::findByName('Article')->where(['category_id' => $model->category_id])->all();

        /** @var Article $article */
        foreach ($leavesArticle as $article){
            if ($model->url == $article->url && ArrayHelper::getValue($model, 'oldAttributes.url') != $model->url){
                $model->addError($attribute, 'Запись с указанным адресом в данной категории уже существует.
                            Измените адрес.');
            }
        }
        /** @var Category $category */
        foreach ($leavesCategory as $category){
            if ($model->url == $category->url){
                $model->addError($attribute, 'Совпадение адреса с категорией. Измените адрес.');
            }
        }
    }
}