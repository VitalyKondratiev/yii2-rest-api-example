<?php

use yii\db\Migration;
use app\models\User;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m190329_152901_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'access_token' => $this->string(128)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        self::seedDemo();
    }

    /**
     * Seed demo user
     */
    private static function seedDemo()
    {
        $users_data = ['admin', 'demo'];
        foreach ($users_data as $user_data) {
            $user = new User();
            $user->username = $user_data;
            $user->setPassword($user_data);
            $user->generateAuthKey();
            if (!$user->save()) {
                throw new Exception();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
