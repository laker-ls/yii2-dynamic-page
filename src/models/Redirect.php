<?php

namespace lakerLS\dynamicPage\models;

use lakerLS\dynamicPage\components\DynamicUrl;
use lakerLS\dynamicPage\components\ModelMap;
use lakerLS\dynamicPage\Module;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;

/**
 * Эта модель для таблицы "dynamic_redirect".
 *
 * @property int $id
 * @property int $category_id
 * @property int $article_id
 * @property string $new_url
 * @property string $old_url
 * @property string $date
 *
 * @property Category $category
 */
class Redirect extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dynamic_redirect';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'article_id'], 'integer'],
            [['date'], 'safe'],
            [['old_url', 'new_url'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'ID категории',
            'article_id' => 'ID статьи',
            'new_url' => 'Новый адрес',
            'old_url' => 'Старый адрес',
            'date' => 'Дата создания',
        ];
    }

    /**
     * Делаем запись в базу данных при изменении уровня вложенности или основного адреса.
     * @param Category|Article $main экземпляр модели Category|Content
     */
    public function urlMain($main)
    {
        $categories = ModelMap::findByName('Category')->where(['<>', 'lvl', 0])->all();

        /**
         * При изменении вложенности через виджет treeManager старый путь становится не актуальным,
         * поэтому делаем повторный запрос.
         */

        $Category = Module::getInstance()->modelMap['Category'];
        if ($main instanceof $Category) {
            /** @var Category $category */
            $category = ModelMap::findByName('Category')->where($main->id)->one();
            $newUrl = DynamicUrl::full($categories, $main);
            $condition = ['category_id' => $main->id];
        } else {
            $category = ModelMap::findByName('Category')->where($main->category_id)->one();
            $newUrl = DynamicUrl::full($categories, $main);
            $condition = ['article_id' => $main->id];
        }

        /** @var Redirect $oldRedirect */
        $oldRedirect = ModelMap::findByName('Redirect')->where($condition)->orderBy(['date' => SORT_DESC])->one();
        if (empty($oldRedirect) || $oldRedirect->new_url != $newUrl) {
            $oldUrl = !empty($oldRedirect) ? $oldRedirect->new_url : null;
            $this->saveModel($main, $oldUrl, $newUrl);
            if ($main instanceof $Category) {
                $this->urlChildren($category, $categories);
            }
        }
    }

    /**
     * Если основная категория изменяет свой адрес, при этом содержит детей, то необходимо сделать новые записи в БД
     * всем детям с актуальными адресами.
     *
     * @param Category $category
     * @param array $categories
     * @throws ServerErrorHttpException
     * @internal param string $newUrl
     */
    private function urlChildren($category, $categories)
    {
        $children = $category->children()->all();
        $childrenContent = ModelMap::findByName('Article')->where(['category_id' => $category->id])->all();
        $allChildren = $children;
        foreach ($childrenContent as $content) {
            array_push($allChildren, $content);
        }
        /** @var Category $child */
        foreach ($children as $child) {
            $childContent = ModelMap::findByName('Article')->where(['category_id' => $child->id])->all();
            foreach ($childContent as $content) {
                array_push($allChildren, $content);
            }
        }

        foreach ($allChildren as $child) {
            $newUrl = DynamicUrl::full($categories, $child);

            if ($child instanceof Category) {
                $condition = ['category_id' => $child['id']];
            } else {
                $condition = ['article_id' => $child['id']];
            }
            /** @var Redirect $oldRedirect */
            $oldRedirect = ModelMap::findByName('Redirect')->where($condition)->orderBy(['date' => SORT_DESC])->one();

            if (!empty($oldRedirect)) {
                $this->saveModel($oldRedirect, $oldRedirect->new_url, $newUrl);
            } else {
                throw new ServerErrorHttpException('Вы удалили необходимые записи в таблице "redirect".
                Пожалуйста создайте запись в таблице "redirect" недостающим категориям и их детям.');
            }
        }
    }

    /**
     * Создание записи в Redirect.
     *
     * @param object $model
     * @param string|null $oldUrl
     * @param string $newUrl
     */
    private function saveModel($model, $oldUrl, $newUrl)
    {
        /**
         * @var Category $Category
         * @var Article $Article
         * @var Redirect $Redirect
         */
        $Category = Module::getInstance()->modelMap['Category'];
        $Article = Module::getInstance()->modelMap['Article'];
        $Redirect = Module::getInstance()->modelMap['Redirect'];

        $redirect = ModelMap::new('Redirect');

        if ($model instanceof $Category) {
            $redirect->category_id = $model->id;
        } elseif ($model instanceof $Redirect && $model->category_id != null) {
            $redirect->category_id = $model->category_id;
        } elseif ($model instanceof $Article) {
            $redirect->article_id = $model->id;
        } else {
            $redirect->article_id = $model->article_id;
        }

        if (isset($oldUrl)) {
            $redirect->old_url = $oldUrl;
        }

        $redirect->new_url = $newUrl;

        if (!$redirect->save()) {
            var_dump($redirect->errors);
        }
    }
}
