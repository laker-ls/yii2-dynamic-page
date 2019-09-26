<?php

namespace lakerLS\dynamicPage\models;

use yii\db\ActiveRecord;
use yii2tech\ar\position\PositionBehavior;

/**
 * Эта модель для таблицы "dynamic_type".
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string $select
 * @property int $category
 * @property int $article
 * @property int $nested
 * @property int $position
 */
class Type extends ActiveRecord
{
    /**
     * Необходимо для установки позиции (перемещения) записей между друг другом.
     * @return array
     */
    public function behaviors()
    {
        return [
            'positionBehavior' => [
                'class' => PositionBehavior::class,
            ],
        ];
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);

        /** @var array $select */
        $select = $this->select;
        if (!empty($select)) {
            $this->select = implode(',', $select);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dynamic_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['category', 'article', 'nested', 'position'], 'integer'],
            [['select'], 'safe'],
            [['name', 'type'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'type' => 'Тип содержимого',
            'select' => 'Активные поля для статей.',
            'category' => 'Разрешено назначать категориям.',
            'article' => 'Разрешено назначать статьям.',
            'nested' => 'Категории могут содержать в себе статьи.'
        ];
    }
}
