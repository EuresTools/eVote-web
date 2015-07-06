<?php

use yii\db\Schema;
use yii\db\Migration;

class m150706_113400_add_admin_account extends Migration
{
    public function up()
    {
        $this->insert('user', ['username' => 'admin',
            'password_hash' => '$2y$13$tGXqc0xkHdB89no1kdFnqujEsDufvJKRLg62CtznRCcWeMRJIz81a',
            'is_admin' => true,
            'auth_key' => '1GUCDO17QosEIa-NZdmShGKce5gKX4Xo',
            'created_at' => new \yii\db\Expression('NOW()'),
            'updated_at' => new \yii\db\Expression('NOW()'),
        ]);
    }

    public function down()
    {
        return true;
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
