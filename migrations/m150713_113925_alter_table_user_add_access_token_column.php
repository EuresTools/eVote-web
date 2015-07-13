<?php

use yii\db\Schema;
use yii\db\Migration;

class m150713_113925_alter_table_user_add_access_token_column extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'access_token', Schema::TYPE_STRING . ' NOT NULL AFTER auth_key');
    }

    public function down()
    {
        $this->dropColumn('user', 'access_token');
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
