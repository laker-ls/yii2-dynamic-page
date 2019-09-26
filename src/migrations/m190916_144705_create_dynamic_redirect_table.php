<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dynamic_redirect}}`.
 */
class m190916_144705_create_dynamic_redirect_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dynamic_redirect}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'article_id' => $this->integer(),
            'new_url' => $this->string(1000),
            'old_url' => $this->string(1000),
            'date' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'FK_dynamic_redirect_dynamic_category',
            'dynamic_redirect',
            'category_id',
            'dynamic_category',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'FK_dynamic_redirect_dynamic_article',
            'dynamic_redirect',
            'article_id',
            'dynamic_article',
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
        $this->dropTable('{{%dynamic_redirect}}');
    }
}
