<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dynamic_type}}`.
 */
class m190916_144201_create_dynamic_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dynamic_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(60)->notNull(),
            'type' => $this->string(60)->notNull(),
            'select' => $this->string(),
            'category' => $this->integer()->notNull()->defaultValue(0),
            'article' => $this->integer()->notNull()->defaultValue(0),
            'nested' => $this->integer()->notNull()->defaultValue(0),
            'position' => $this->integer()->notNull(),
        ]);

        $this->insert('{{%dynamic_type}}', [
            'name' => '---------------------',
            'type' => 'static',
            'category' => 1,
            'position' => 1
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dynamic_type}}');
    }
}
