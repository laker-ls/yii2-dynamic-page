<?php

namespace lakerLS\dynamicPage\controllers;

use lakerLS\dynamicPage\abstractClasses\CrudController;
use lakerLS\dynamicPage\components\ModelMap;
use lakerLS\dynamicPage\models\Article;
use lakerLS\dynamicPage\models\search\ArticleSearch;
use lakerLS\dynamicPage\models\Type;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * ArticleController реализует CRUD для модели Article.
 */
class ArticleController extends CrudController
{
    /**
     * Создание новой модели Article.
     * Вне зависимости от результата попытки создание записи, будет произведен редирект на указанный адрес в GET запросе.
     * При успешном создании записи возвращается алерт успешного создания, в случае ошибки возвращается алерт ошибки.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var Article $model */
        $model = ModelMap::new('Article');

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->save()) {
                $this->alert('success', 'create');
            } else {
                $this->alert('danger', 'create');
            }
            return $this->redirect(Yii::$app->request->get('redirect'));
        }

        $select = ModelMap::findByName('Type')->where(['type' => Yii::$app->request->get('type')])->one();
        $typeField = explode(",", ArrayHelper::getValue($select, 'select'));
        $typeDropDown = ModelMap::findByName('Type')->where(['article' => '1'])->all();

        return $this->render('create', [
            'model' => $model,
            'typeField' => !empty($typeField[0]) ? $typeField : [],
            'typeDropDown' => $typeDropDown,
        ]);
    }

    /**
     * Обновление существющей модели Article.
     * Вне зависимости от результата попытки редактирования записи,
     * будет произведен редирект на указанный адрес в GET запросе.
     * При успешном создании записи возвращается алерт успешного создания, в случае ошибки возвращается алерт ошибки.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        /** @var Article $model */
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->save()) {
                $this->alert('success', 'update');
            } else {
                $this->alert('danger', 'update');
            }

            return $this->redirect(Yii::$app->request->get('redirect'));
        }

        $typeDropDown = ModelMap::findByName('Type')->where(['article' => '1'])->all();

        return $this->render('update', [
            'model' => $model,
            'typeField' => $this->getType($model->type),
            'typeDropDown' => $typeDropDown,
        ]);
    }

    /**
     * Удаление существующей записи, с редиректом на адрес, который был указан в GET запросе.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            $this->alert('success', 'delete');
        } else {
            $this->alert('danger', 'delete');
        }

        return $this->redirect('/' . Yii::$app->request->get('redirect'));
    }

    /**
     * Создание статьи через ajax, вне админ-панели.
     * Запись производится через стандартный `actionCreate`.
     *
     * @param integer $categoryId
     * @param string $type
     * @param string $currentUrl
     * @return string
     */
    public function actionCreateAjax($categoryId, $type, $currentUrl)
    {
        $model = ModelMap::new('Article');

        /** @var Type $type */
        $type = ModelMap::findByName('Type')->where(['type' => $type])->andWhere(['article' => '1'])->one();
        $typeDropDown = ModelMap::findByName('Type')->where(['article' => '1'])->all();

        Yii::$app->assetManager->bundles['lakerLS\dynamicPage\DynamicPageAsset']->js = [];
        Yii::$app->assetManager->bundles = ['yii\web\JqueryAsset' => false];

        return $this->renderAjax('create-ajax', [
            'model' => $model,
            'typeField' => $this->getType($type->type),
            'categoryId' => $categoryId,
            'type' => $type,
            'currentUrl' => $currentUrl,
            'typeDropDown' => $typeDropDown
        ]);
    }

    /**
     * Редактирование статьи через ajax, вне админ-панели.
     * Запись производится через стандартный `actionUpdate`.
     *
     * @param integer $id
     * @param string $currentUrl
     * @return string
     */
    public function actionUpdateAjax($id, $currentUrl)
    {
        $model = $this->findModel($id);

        Yii::$app->assetManager->bundles['lakerLS\dynamicPage\DynamicPageAsset']->js = [];
        Yii::$app->assetManager->bundles = ['yii\web\JqueryAsset' => false];

        return $this->renderAjax('update-ajax', [
            'model' => $model,
            'typeField' => $this->getType($model->type),
            'currentUrl' => $currentUrl,
        ]);
    }

    /**
     * Рендерим модальное окно на странице, в котором будет форма для создания/редактирования статей.
     */
    public function actionModalRender()
    {
        return $this->renderPartial('overriding/_modal');
    }

    /**
     * Реализация родительского абстрактного метода.
     * Передается актуальный путь к model текущего контроллера.
     *
     * @return Article
     */
    protected function model()
    {
        /** @var Article $model */
        $model = ModelMap::new('Article');
        return $model;
    }

    /**
     * Реализация родительского абстрактного метода.
     * Передается актуальный путь к modelSearch текущего контроллера.
     *
     * @return ArticleSearch
     */
    protected function modelSearch()
    {
        $modelSearch = new ArticleSearch();
        return $modelSearch;
    }

    /**
     * Получить массив с полями, которые необходимо рендерить в форме создания/редактирования модели.
     * @param string $type
     * @return array
     */
    private function getType($type)
    {
        $select = ModelMap::findByName('Type')->where(['type' => $type])->one();
        $typeField = explode(",", ArrayHelper::getValue($select, 'select')); //делим на массивы

        return $typeField;
    }
}
