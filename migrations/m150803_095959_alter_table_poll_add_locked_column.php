<?php

use yii\db\Schema;
use yii\db\Migration;

class m150803_095959_alter_table_poll_add_locked_column extends Migration
{
    public function up()
    {
        $this->addColumn('poll', 'locked', Schema::TYPE_INTEGER .' AFTER organizer_id');
    }

    public function down()
    {
        $this->dropColumn('poll', 'locked');
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
