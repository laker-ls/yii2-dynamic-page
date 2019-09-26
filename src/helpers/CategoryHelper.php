<?php

namespace lakerLS\dynamicPage\helpers;

use lakerLS\dynamicPage\components\DynamicUrl;
use yii\db\Exception;

/**
 * Помощник для выборки из списка всех категорий, для избежания дополнительных запросов к базе.
 *
 * Class CategoryHelper
 * @package app\myFolders\widgets
 */
class CategoryHelper
{
    /**
     * Получаем категорию, в которой находится статья.
     *
     * @param integer $category_id
     * @param array $allCategories
     * @return object
     * @throws Exception
     */
    public static function getParentArticle($category_id, $allCategories)
    {
        foreach ($allCategories as $value) {
            if ($value->id == $category_id) {
                $result = $value;
            }
        }

        if (isset($result)) {
            return $result;
        } else {
            throw new Exception('У данной статьи отсутствует категория.');
        }

    }

    /**
     * Получаем всех родителей необходимой категории
     *
     * @param array $categories
     * @param object $children категория, у которой необходимо найти родителей.
     * @return array
     */
    public static function getParents($categories, $children)
    {
        foreach ($categories as $category) {
            if ($children->lft > $category->lft && $children->rgt < $category->rgt) {
                $result[] = $category;
            }
        }

        return isset($result) ? $result : [];
    }

    /**
     * Получаем всех детей необходимой категории.
     *
     * @param array $categories
     * @param object $parent чьих детей нам необходимо получить.
     * @return array
     */
    public static function getChildrens($categories, $parent)
    {
        foreach ($categories as $category) {
            if ($parent->lft < $category->lft && $parent->rgt > $category->rgt) {
                $result[] = $category;
            }
        }

        return isset($result) ? $result : [];
    }

    /**
     * Получаем необходимую категорию по id.
     *
     * @param array $categories
     * @param integer $id
     * @return object|null
     */
    public static function getById($categories, $id)
    {
        foreach ($categories as $category) {
            if ($category->id == $id) {
                $result = $category;
            }
        }

        return isset($result) ? $result : null;
    }

    /**
     * Получаем необходимые категории по типу.
     *
     * @param array $categories
     * @param string $type
     * @return array
     */
    public static function getByType($categories, $type)
    {
        foreach ($categories as $category) {
            if ($category->type == $type) {
                $result[] = $category;
            }
        }

        return isset($result) ? $result : [];
    }

    /**
     * Получаем необходимую категорию по url.
     * @param array $categories
     * @param string $url
     * @return array
     */
    public static function getByUrl($categories, $url)
    {
        foreach ($categories as $category) {
            if ($category->url == $url) {
                $result[] = $category;
            }
        }

        return isset($result) ? $result : [];
    }

    /**
     * Получаем необходимую категорию по полному url.
     * @param array $categories
     * @param string $fullUrl
     * @return array
     */
    public static function getByFullUrl($categories, $fullUrl)
    {
        foreach ($categories as $possibleCat) {
            if (DynamicUrl::full($categories, $possibleCat) == '/' . $fullUrl) {
                $result = $possibleCat;
            }
        }

        return isset($result) ? $result : [];
    }
}