<?php

use yii\db\Schema;
use yii\db\Migration;

class m150713_075858_alter_table_code_add_sent_column extends Migration
{
    public function up()
    {
        $this->addColumn('code', 'sent_at', Schema::TYPE_DATETIME .' AFTER code_status');
    }

    public function down()
    {
        $this->dropColumn('code', 'sent_at');
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
