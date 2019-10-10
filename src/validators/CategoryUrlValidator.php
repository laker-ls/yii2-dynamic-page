<?php

namespace lakerLS\dynamicPage\validators;

use kartik\tree\TreeView;
use lakerLS\dynamicPage\components\ModelMap;
use lakerLS\dynamicPage\models\Category;
use Yii;
use yii\validators\Validator;

class CategoryUrlValidator extends Validator
{
    /**
     * Проверка url категории на уникальность.
     *
     * Url должен быть уникальным в пределах текущего уровня вложенности. Проверка осуществляется среди категории,
     * а так же среди статей, которые расположены на одной глубине.
     *
     * Допускается совпадение url на разных уровнях вложенности.
     *
     * Уровень категории обозначен в столбце `lvl` таблицы Category. Уровень статьи обозначен неявно, а именно:
     * $lvl + 1 , где $lvl глубина категории, которая является родителем статьи.
     *
     * @param object $model
     * @param string $attribute
     * @return bool
     */
    public function validateAttribute($model, $attribute)
    {
        $post = Yii::$app->request->post();
        $currentCategory = $model;

        if (isset($post['parentKey'])) { // Создание или редактирование категории.
            $isRoot = $post['parentKey'] == TreeView::ROOT_KEY
                || !empty($currentCategory->id)
                && !empty($currentCategory->root)
                && $currentCategory->id == $currentCategory->root;

            if ($isRoot) {
                if (!empty($currentCategory->id)) {
                    $model->addError($attribute, 'Нельзя редактировать корневую дерикторию.');
                    return false;
                } else {
                    return true;
                }
            }

            $this->validateCUCategory($model, $attribute, $post['parentKey']);
        } elseif (isset($post['idFrom'])) { // Перемещение категории.
            $this->validateMoveCategory($model, $attribute, $post);
        }

        return true;
    }

    /**
     * Валидация на совпадение url при создании и редактировании категории.
     *
     * @param object $model
     * @param string $attribute
     * @param integer $parentId
     */
    private function validateCUCategory($model, $attribute, $parentId)
    {
        /** @var Category $parent */
        $parent = ModelMap::findByName('Category')->where(['id' => $parentId])->one();

        $leavesCategory = $parent->children(1)->all();
        $leavesArticle = ModelMap::findByName('Article')->where(['category_id' => $parentId])->all();

        $this->validateSelection($model, $attribute, $leavesCategory, $leavesArticle);
    }

    /**
     * Валидация на совпадение url при перемещении категории в дереве.
     *
     * @param object $model
     * @param string $attribute
     * @param array $post
     */
    private function validateMoveCategory($model, $attribute, $post)
    {
        /** @var Category $parent */
        /** @var Category $movingCategory */
        if ($post['dir'] == 'l') {
            $movingCategory = ModelMap::findByName('Category')->where(['id' => $post['idFrom']])->one();
            $parent = $movingCategory->parents(1)->one();
            if ($parent->id != $parent->root) {
                $parent = $parent->parents(1)->one();
            }
            $leavesCategory = $parent->children(1)->all();
            $leavesArticle = ModelMap::findByName('Article')->where(['category_id' => $parent->id])->all();
        } elseif ($post['dir'] == 'r') {
            $parent = ModelMap::findByName('Category')->where(['id' => $post['idTo']])->one();
            $leavesCategory = $parent->children(1)->all();
            $leavesArticle = ModelMap::findByName('Article')->where(['category_id' => $parent->id])->all();
        } else {
            $leavesCategory = [];
            $leavesArticle = [];
        }
        $this->validateSelection($model, $attribute, $leavesCategory, $leavesArticle);
    }

    /**
     * Подбор всех категорий и статей на уровне, в котором осуществляются действия.
     *
     * @param object $model
     * @param string $attribute
     * @param array $leavesCategory
     * @param array $leavesArticle
     */
    private function validateSelection($model, $attribute, $leavesCategory, $leavesArticle)
    {
        $currentCategory = $model;

        if (isset($leavesCategory)) {
            foreach ($leavesCategory as $category) {
                if ($currentCategory->url == $category->url && $currentCategory->id != $category->id) {
                    $model->addError($attribute,
                        'Совпадение адреса с адресом другой категориий. Измените адрес.');
                }
            }
        }
        if (isset($leavesArticle)) {
            foreach ($leavesArticle as $article) {
                if ($currentCategory->url == $article->url) {
                    $model->addError($attribute,
                        'Совпадение адреса с адресом статьи. Измените адрес.');
                }
            }
        }
    }
}