<?php

namespace lakerLS\dynamicPage\models\search;

use lakerLS\dynamicPage\components\ModelMap;
use lakerLS\dynamicPage\models\Type;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TypeSearch represents the model behind the search form of `app\models\Type`.
 */
class TypeSearch extends Type
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ModelMap::findByName('Type');

        // add conditions that should always apply here

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

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
