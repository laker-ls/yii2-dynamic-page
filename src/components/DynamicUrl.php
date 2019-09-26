<?php

namespace lakerLS\dynamicPage\components;

use lakerLS\dynamicPage\models\Category;
use lakerLS\dynamicPage\models\Article;

/**
 * Получения полных url для динамических категорий/статей без дополнительных запросов к базе данных.
 * Актуально, если вы получаете все категории одним запросом.
 */
class DynamicUrl
{
    /**
     * Получаем полный адрес до категории или статьи.
     *
     * @param array $allCategories
     * @param Article|Category $categoryOrArticle
     * @return string
     */
    public static function full($allCategories, $categoryOrArticle)
    {
        if ($categoryOrArticle instanceof Category) {
            $category = $categoryOrArticle;
        } else {
            $category = self::category($allCategories, $categoryOrArticle->category_id);
        }
        $parents = self::parents($allCategories, $category);
        $url = null;
        if ($parents != null) {
            $url = '/' . implode('/', $parents);
        }
        if ($categoryOrArticle instanceof Category) {
            return $url . '/' . $category->url;
        } else {
            return $url . '/' . $category->url . '/' . $categoryOrArticle->url;
        }
    }

    /**
     * Получаем категорию, в которой лежит статья.
     *
     * @param array $allCategory все существующие категории
     * @param object $categoryId столбец с id категории
     * @return object категория в которой находится статья
     */
    public static function category($allCategory, $categoryId)
    {
        $category = null;
        foreach ($allCategory as $value) {
            if ($value->id == $categoryId) {
                $category = $value;
            }
        }
        return $category;
    }

    /**
     * Получаем всех родителей категории.
     *
     * @param array $allCategory все существующие категории
     * @param object $currentCategory категория у которой нужно найти родителей
     * @return array|null массив из всех родителей
     */
    public static function parents($allCategory, $currentCategory)
    {
        $result = null;
        foreach ($allCategory as $cat) {
            if ($currentCategory->lft > $cat->lft && $currentCategory->rgt < $cat->rgt) {
                $result[] = $cat->url;
            }
        }
        return $result;
    }
}