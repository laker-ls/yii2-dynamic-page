<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dynamic_category}}`.
 */
class m190916_135550_create_dynamic_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dynamic_category}}', [
            'id' => $this->primaryKey(),
            'root' => $this->integer(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'lvl' => $this->smallInteger(5)->notNull(),

            'type' => $this->string()->notNull(),
            'name' => $this->string(70)->notNull(),
            'url' => $this->string()->notNull(),
            'image' => $this->string(),
            'title' => $this->string(70),
            'description' => $this->string(150),
            'keyword' => $this->string(),
            'date' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),

            'icon' => $this->string()->defaultValue(false),
            'icon_type' => $this->tinyInteger(1)->notNull()->defaultValue(1),
            'active' => $this->tinyInteger(1)->notNull()->defaultValue(true),
            'selected' => $this->tinyInteger(1)->notNull()->defaultValue(false),
            'disabled' => $this->tinyInteger(1)->notNull()->defaultValue(false),
            'readonly' => $this->tinyInteger(1)->notNull()->defaultValue(false),
            'visible' => $this->tinyInteger(1)->notNull()->defaultValue(true),
            'collapsed' => $this->tinyInteger(1)->notNull()->defaultValue(true),
            'movable_u' => $this->tinyInteger(1)->notNull()->defaultValue(true),
            'movable_d' => $this->tinyInteger(1)->notNull()->defaultValue(true),
            'movable_l' => $this->tinyInteger(1)->notNull()->defaultValue(true),
            'movable_r' => $this->tinyInteger(1)->notNull()->defaultValue(true),
            'removable' => $this->tinyInteger(1)->notNull()->defaultValue(true),
            'removable_all' => $this->tinyInteger(1)->notNull()->defaultValue(true),
            'child_allowed' => $this->tinyInteger(1)->notNull()->defaultValue(true),
        ]);

        $this->createIndex('tree_NK1', 'dynamic_category', 'root');
        $this->createIndex('tree_NK2', 'dynamic_category', 'lft');
        $this->createIndex('tree_NK3', 'dynamic_category', 'rgt');
        $this->createIndex('tree_NK4', 'dynamic_category', 'lvl');
        $this->createIndex('tree_NK5', 'dynamic_category', 'active');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dynamic_category}}');
    }
}
