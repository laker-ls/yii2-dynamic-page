<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dynamic_article}}`.
 */
class m190916_135945_create_dynamic_article_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dynamic_article}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'type' => $this->string(60)->notNull(),
            'name' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'image' => $this->text(),
            'text' => $this->text(),
            'title' => $this->string(),
            'description' => $this->string(500),
            'keyword' => $this->string(),
            'author_id' => $this->integer()->notNull(),
            'viewer' => $this->integer(),
            'position' => $this->integer()->notNull(),
            'date' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'FK_dynamic_article_dynamic_category',
            'dynamic_article',
            'category_id',
            'dynamic_category',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dynamic_article}}');
    }
}
