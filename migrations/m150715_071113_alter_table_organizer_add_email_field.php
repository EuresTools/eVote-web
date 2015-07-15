<?php

use yii\db\Schema;
use yii\db\Migration;

class m150715_071113_alter_table_organizer_add_email_field extends Migration
{
    public function up()
    {
        $this->addColumn('organizer', 'email', Schema::TYPE_STRING .' NOT NULL AFTER name');
    }

    public function down()
    {
        $this->dropColumn('organizer', 'email');
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
