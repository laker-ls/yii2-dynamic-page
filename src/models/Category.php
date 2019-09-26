<?php

namespace lakerLS\dynamicPage\models;

use kartik\tree\models\Tree;
use kartik\tree\models\TreeQuery;
use lakerLS\dynamicPage\components\ModelMap;
use lakerLS\dynamicPage\validators\CategoryUrlValidator;

/**
 * Эта модель для таблицы "dynamic_category".
 *
 * @property int $id
 * @property int $root
 * @property int $lft
 * @property int $rgt
 * @property int $lvl
 *
 * @property string $type
 * @property string $name
 * @property string $url
 * @property string $image
 * @property string $title
 * @property string $description
 * @property string $keyword
 * @property string $date
 *
 * @property string $icon
 * @property int $icon_type
 * @property int $active
 * @property int $selected
 * @property int $disabled
 * @property int $readonly
 * @property int $visible
 * @property int $collapsed
 * @property int $movable_u
 * @property int $movable_d
 * @property int $movable_l
 * @property int $movable_r
 * @property int $removable
 * @property int $removable_all
 * @property int $child_allowed
 *
 * @method TreeQuery children($depth = null)
 */
class Category extends Tree
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dynamic_category';
    }

    /**
     * Делаем запись в таблицу "redirect", где хранятся новые и старые адреса, для переадресации.
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $redirect = ModelMap::new('Redirect');
        $redirect->urlMain($this);
    }

    /**
     * Правила валидации полей.
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['type', 'url', 'name'], 'required'];
        $rules[] = [['name', 'title'], 'string', 'max' => 70];
        $rules[] = [['type', 'image', 'description', 'keyword', 'url'], 'string', 'max' => 255];
        $rules[] = [['date'], 'safe'];

        $rules[] = [['url'], 'match', 'pattern' => '/^[^а-яА-ЯA-Z\?]+$/', 'message' => 'Используйте англ. символы'];
        $rules[] = [['url'], CategoryUrlValidator::class];
        return $rules;
    }

    /**
     * Заголовки полей.
     */
    public function attributeLabels()
    {
        $attr = parent::attributeLabels();
        $attr['type'] = 'Тип';
        $attr['name'] = 'Наименование';
        $attr['url'] = 'Адрес';
        $attr['image'] = 'Изображение';
        $attr['title'] = 'Заголовок для поисковика';
        $attr['description'] = 'Краткое описание';
        $attr['keyword'] = 'Теги';
        $attr['date'] = 'Дата создания';
        return $attr;
    }
}
