<?php

use yii\db\Schema;
use yii\db\Migration;

class m150706_130827_extendTablesOwnershipColumns extends Migration
{

    protected $tablesTodo = [
            'user',
            'poll',
            'option',
            'organizer',
            'member',
            'contact',
            'code',
            'vote',
            //'vote_option',
        ];


    public function up()
    {
        foreach ($this->tablesTodo as $table) {
            $this->addColumn($table, 'created_by', Schema::TYPE_INTEGER .' AFTER updated_at');
            $this->addColumn($table, 'updated_by', Schema::TYPE_INTEGER .' AFTER created_by');



            // add default indexes created_by und updated_by
            // $this->createIndex('idx_'.$table.'_created_by', $table, 'created_by', $unique = false);
            // $this->createIndex('idx_'.$table.'_updated_by', $table, 'updated_by', $unique = false);

            $this->addForeignKey($table.'_created_by', $table, 'created_by', 'user', 'id');
            $this->addForeignKey($table.'_updated_by', $table, 'updated_by', 'user', 'id');
        }


    }

    public function down()
    {


        foreach ($this->tablesTodo as $table) {

            $this->dropForeignKey($table.'_created_by', $table);
            $this->dropForeignKey($table.'_updated_by', $table);


            $this->dropColumn($table, 'created_by');
            $this->dropColumn($table, 'updated_by');
        }

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
