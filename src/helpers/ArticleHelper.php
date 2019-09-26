<?php

namespace lakerLS\dynamicPage\helpers;

use lakerLS\dynamicPage\components\ModelMap;

class ArticleHelper
{
    /**
     * Получаем список категорий, в которых можно создавать статьи. Для dropDown input в админ панели создания статей.
     */
    public static function categoryDropDown()
    {
        $canHaveArticle = ModelMap::findByName('Type')->where(['nested' => '1'])->select('type')->asArray()->all();
        $categoryDropDown = [];
        $currentArray = [];
        foreach($canHaveArticle as $nested) {
            $prevArray = $currentArray;
            $currentArray = ModelMap::findByName('Category')
                ->where(['<>', 'lvl', '0'])->andWhere(['type' => $nested['type']])->select(['id', 'name'])->asArray()->all();
            $categoryDropDown = array_merge($prevArray, $currentArray);
        }

        return isset($categoryDropDown) ? $categoryDropDown : [];
    }
}