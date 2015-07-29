<?php

use yii\db\Schema;
use yii\db\Migration;

class m150729_110959_change_foreign_keys_in_vote_option_table extends Migration
{
    public function up()
    {
        $this->dropForeignKey('rel_vote_id', 'vote_option');
        $this->dropForeignKey('rel_option_id', 'vote_option');

        $this->addForeignKey('rel_vote_id', 'vote_option', 'vote_id', 'vote', 'id', $delete = 'CASCADE');
        $this->addForeignKey('rel_option_id', 'vote_option', 'option_id', 'option', 'id', $delete = 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('rel_vote_id', 'vote_option');
        $this->dropForeignKey('rel_option_id', 'vote_option');

        $this->addForeignKey('rel_vote_id', 'vote_option', 'vote_id', 'vote', 'id');
        $this->addForeignKey('rel_option_id', 'vote_option', 'option_id', 'option', 'id');

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
