<?php

namespace lakerLS\dynamicPage\models;

use lakerLS\dynamicPage\components\ModelMap;
use lakerLS\dynamicPage\validators\ArticleUrlValidator;
use yii\db\ActiveRecord;
use yii2tech\ar\position\PositionBehavior;

/**
 * Эта модель для таблицы "dynamic_article".
 *
 * @property int $id
 * @property int $category_id
 * @property string $type
 * @property string $name
 * @property string $url
 * @property string $image
 * @property string $text
 * @property string $title
 * @property string $description
 * @property string $keyword
 * @property int $author_id
 * @property int $viewer
 * @property int $position
 * @property string $date
 */
class Article extends ActiveRecord
{
    /**
     * Делаем запись в таблицу "redirect", где хранятся новые и старые адреса, для переадресации.
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $redirect = ModelMap::newObject('Redirect');
        $redirect->urlMain($this);
    }

    /**
     * Необходимо для установки позиции (перемещения) записей между друг другом.
     * @return array
     */
    public function behaviors()
    {
        return [
            'positionBehavior' => [
                'class' => PositionBehavior::class,
                'groupAttributes' => ['category_id']
            ],
        ];
    }

    public static function tableName()
    {
        return 'dynamic_article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'author_id', 'viewer', 'position'], 'integer'],
            [['image', 'text'], 'string'],
            [['date'], 'safe'],
            [['type', 'name', 'title', 'keyword', 'url'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
            [['category_id', 'type', 'name', 'url'], 'required'],

            [['url'], 'match', 'pattern' => '/^[^а-яА-ЯA-Z\?]+$/', 'message' => 'Используйте англ. символы'],
            [['url'], ArticleUrlValidator::class],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Категория',
            'type' => 'Дизайн',
            'name' => 'Заголовок',
            'url' => 'Адрес',
            'image' => 'Изображение',
            'text' => 'Текст',
            'title' => 'Заголовок для поисковика',
            'description' => 'Краткое описание',
            'keyword' => 'Ключевые слова',
        ];
    }
}
