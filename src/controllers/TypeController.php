<?php

namespace lakerLS\dynamicPage\controllers;

use lakerLS\dynamicPage\components\ModelMap;
use lakerLS\dynamicPage\controllers\crud\CrudControllerAbstract;
use lakerLS\dynamicPage\models\search\TypeSearch;
use lakerLS\dynamicPage\models\Type;

/**
 * TypeController реализует CRUD для модели Type, с помощью которой устанавливается тип категории.
 */
class TypeController extends CrudControllerAbstract
{
    /**
     * Если удаляемый тип содержимого используется в категориях или статьях, произойдет рендер представления с ошибкой.
     *
     * @param int $id
     * @return mixed|string
     */
    public function actionDelete($id)
    {
        /** @var Type $type */
        $type = ModelMap::findByName('Type')->where(['id' => $id])->one();
        $categories = ModelMap::findByName('Category')->where(['type' => $type->type])->all();
        $articles = ModelMap::findByName('Article')->where(['type' => $type->type])->all();

        if (!empty($categories) || !empty($articles)) {
            return $this->render('error', [
                'categories' => !empty($categories) ? $categories : [],
                'articles' => !empty($articles) ? $articles : [],
            ]);
        }

        return parent::actionDelete($id);
    }

    /**
     * Реализация родительского абстрактного метода.
     * Передается актуальный путь к model текущего контроллера.
     *
     * @return Type
     */
    protected function model()
    {
        /** @var Type $model */
        $model = ModelMap::newObject('Type');
        return $model;
    }

    /**
     * Реализация родительского абстрактного метода.
     * Передается актуальный путь к modelSearch текущего контроллера.
     *
     * @return TypeSearch
     */
    protected function modelSearch()
    {
        $modelSearch = new TypeSearch();
        return $modelSearch;
    }
}
