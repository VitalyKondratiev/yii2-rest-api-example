<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%calculation}}`.
 */
class m190330_081654_calculation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%calculation}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'number' => $this->integer(),
            'data' => $this->string()->notNull(),
            'split_point_index' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%calculation}}');
    }
}
