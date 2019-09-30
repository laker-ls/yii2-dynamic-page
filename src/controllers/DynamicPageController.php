<?php

namespace lakerLS\dynamicPage\controllers;

use lakerLS\dynamicPage\components\ModelMap;
use lakerLS\dynamicPage\helpers\CategoryHelper;
use lakerLS\dynamicPage\models\Article;
use lakerLS\dynamicPage\models\Category;
use lakerLS\dynamicPage\models\search\ArticleSearch;
use lakerLS\dynamicPage\Module;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * DynamicPageController реализует организацию путей для переадресации.
 */
class DynamicPageController extends Controller
{
    /**
     * Категории из этой переменной доступны в layout'e
     * @var array $allCategories
     */
    public $allCategories;

    /**
     * Свойство, которое необходимо для виджета https://github.com/laker-ls/yii2-pencil
     * @var int $categoryId
     */
    public $categoryId;

    /**
     * Передаем в свойство $allCategories все существующие категории, за исключением корня.
     *
     * @param string $id
     * @param Module $module
     * @param array $config
     */
    public function __construct($id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->allCategories = ModelMap::findByName('Category')
            ->where(['<>', 'lvl', 0])->orderBy(['lft' => SORT_ASC])->all();
    }

    /**
     * Отображения категории. Если страница находится по другому адресу, то производится редирект.
     * Если по указанному адресу не найдена статическая страница, но найдено совпадение в динамических с типом "static"
     * будет выброшено исключение.
     *
     * @param Category $model
     * @param null|string $redirect
     * @return mixed
     * @throws HttpException
     */
    public function actionCategory(Category $model, $redirect = null)
    {
        if (!empty($redirect)) {
            return $this->redirect($redirect, 301);
        }

        $modelSearch = new ArticleSearch();
        $dataProvider = $modelSearch->inCategory($model->id, Yii::$app->request->queryParams);
        $this->categoryId = $model->id;

        $this->trigger(self::EVENT_BEFORE_ACTION);
        if ($model->type != 'static') {
            return $this->render($model->type . '/' . $model->type . 'Category', [
                'category' => $model,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            throw new HttpException(422, 'Страница не может быть отображена.');
        }

    }

    /**
     * Отображение статьи. Если страница находится по другому адресу, то производится редирект.
     *
     * @param Article $model
     * @param null|string $redirect
     * @return mixed
     */
    public function actionArticle(Article $model, $redirect = null)
    {
        if (!empty($redirect)) {
            return $this->redirect($redirect, 301);
        }

        $categoryArticle = CategoryHelper::getParentArticle($model->category_id, $this->allCategories);
        $this->categoryId = $categoryArticle->id;

        $this->trigger(self::EVENT_BEFORE_ACTION);
        return $this->render($model->type . '/' . $model->type . 'Article', [
            'category' => $categoryArticle,
            'article' => $model,
        ]);
    }
}