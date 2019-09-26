<?php

namespace lakerLS\dynamicPage\components;

use lakerLS\dynamicPage\helpers\CategoryHelper;
use lakerLS\dynamicPage\models\Redirect;
use Yii;
use yii\web\UrlRuleInterface;
use yii\base\BaseObject;

class DynamicPage extends BaseObject implements UrlRuleInterface
{
    /**
     * Проверяем, является ли страница динамической, если да, то обрабатываем соответствующе для отображения
     * необходимой страницы.
     *
     * Получаем родителей текущей категории или категории в которой находится текущая запись.
     * Сверяем полный путь текущий с полученным из базы данных, нужен для определения где мы находимся,
     * так как у записи в БД может быть одинаковый url, но разная вложенность.
     *
     * Если путь был найден, то переводим на необходимый контроллер, в противном случае вызываем переадресацию.
     *
     * @param \yii\web\UrlManager $manager
     * @param \yii\web\Request $request
     * @return array|bool
     */
    public function parseRequest($manager, $request)
    {
        $allCategories = ModelMap::findByName('Category')->where(['<>', 'lvl', 0])->all();

        $url = explode('/', $request->pathInfo);
        $url = array_pop($url);

        $category = CategoryHelper::getByFullUrl($allCategories, $request->pathInfo);
        $article = ModelMap::findByName('Article')->where(['url' => $url])->one();

        if (!empty($category)) {
            $action = ['dynamic-page/dynamic-page/category', ['model' => $category]];
        } elseif (!empty($article)) {
            $action = ['dynamic-page/dynamic-page/article', ['model' => $article]];
        } else {
            $action = $this->redirect($allCategories);
        }

        return $action;
    }

    /**
     * Переадресация на актуальный адрес, если таковой существует, в противном случае ошибка 404.
     *
     * @param array $allCategories
     * @return array|bool
     */
    private function redirect($allCategories)
    {
        /** @var Redirect $oldUrl */
        $oldUrl = ModelMap::findByName('Redirect')->where(['old_url' => '/' . Yii::$app->request->pathInfo])->one();

        if (isset($oldUrl)) {
            $dynamicPage = null;

            if ($oldUrl->category_id != null) {
                $condition = ['category_id' => $oldUrl->category_id];
            } else {
                $condition = ['article_id' => $oldUrl->article_id];
            }
            /** @var Redirect $redirect */
            $redirect = ModelMap::findByName('Redirect')->where($condition)->orderBy(['date' => SORT_DESC])->one();

            foreach ($allCategories as $cat) {
                if (DynamicUrl::full($allCategories, $cat)) {
                    $model = $cat;
                    $dynamicPage = 'dynamic-page/dynamic-page/category';
                }
            }

            if (empty($model)) {
                $model = ModelMap::findByName('Category')->where(['id' => $redirect->article_id])->one();
                $dynamicPage = 'dynamic-page/dynamic-page/article';
            }

            $action = [$dynamicPage, ['model' => $model, 'redirect' => $redirect->new_url]];
        } else {
            $action = false;
        }

        return $action;
    }

    /**
     * TODO: Возможно есть способ реализовать адреса для динамических страниц через эту функцию без костылей.
     *
     * @param \yii\web\UrlManager $manager
     * @param string $route
     * @param array $params
     * @return boolean
     */
    public function createUrl($manager, $route, $params)
    {
        return false;
    }
}