<?php

use yii\db\Schema;
use yii\db\Migration;

class m150710_133043_alter_table_code_rename_is_valid_to_status extends Migration
{
    public function up()
    {
        $this->renameColumn('code', 'is_valid', 'code_status');
    }

    public function down()
    {
        $this->renameColumn('code', 'code_status', 'is_valid');
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
