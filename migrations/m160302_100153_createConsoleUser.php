<?php

use yii\db\Migration;
use app\models\User;

class m160302_100153_createConsoleUser extends Migration
{
    public function up()
    {
        $this->insert('user', ['username' => 'console_user',
            'password_hash' => '',
            'is_admin' => true,
            'auth_key' => null,
            'created_at' => new \yii\db\Expression('NOW()'),
            'updated_at' => new \yii\db\Expression('NOW()'),
        ]);
    }

    public function down()
    {
        $this->delete('user', ['username' => 'console_user']);
        return true;
    }
}
