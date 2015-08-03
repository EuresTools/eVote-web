<?php

use yii\db\Schema;
use yii\db\Migration;

class m150803_082824_alter_table_poll_add_info_column extends Migration
{
    public function up()
    {
        $this->addColumn('poll', 'info', Schema::TYPE_TEXT .' AFTER question');
    }

    public function down()
    {
        $this->dropColumn('poll', 'info');
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
