<?php

namespace lakerLS\dynamicPage\models\search;

use lakerLS\dynamicPage\components\ModelMap;
use lakerLS\dynamicPage\models\Article;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;

/**
 * ArticleSearch represents the model behind the search form of `app\models\Article`.
 */
class ArticleSearch extends Article
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'category_id'], 'string'],
        ];
    }

    /**
     * Экземляр пагинации с настройками для корректного отображения и работы пагинации на странице.
     *
     * @param array $category
     * @return Pagination
     */
    public function pagination($category)
    {
        $pagination = new Pagination([
            'defaultPageSize' => $this->search($category)->pagination->pageSize,
            'totalCount' => $this->search($category)->query->count(),
            'route' => Yii::$app->request->pathInfo
        ]);

        return $pagination;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     * @internal param object $category
     * @internal param array $params
     */
    public function search($params)
    {
        $query = ModelMap::findByName('Article');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'position' => SORT_DESC,
                ]
            ],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');

            return $dataProvider;
        }
        $query->andFilterWhere(['category_id' => $this->category_id,])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    /**
     * Поиск записей, которые принадлежат определенной категории.
     *
     * @param integer $categoryId
     * @param array $params
     * @return ActiveDataProvider
     * @internal param object $category
     * @internal param array $params
     */
    public function inCategory($categoryId, $params)
    {
        $query = ModelMap::findByName('Article')->where(['category_id' => $categoryId]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'position' => SORT_DESC,
                ]
            ],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}
