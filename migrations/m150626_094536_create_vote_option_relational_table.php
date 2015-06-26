<?php

use yii\db\Schema;
use yii\db\Migration;

class m150626_094536_create_vote_option_relational_table extends Migration
{
    public function up() {
        $this->createTable('vote_option', [
            'id' => Schema::TYPE_PK,
            'vote_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'option_id' => Schema::TYPE_INTEGER . ' NOT NULL'
        ]);

        $this->addForeignKey('rel_vote_id', 'vote_option', 'vote_id', 'vote', 'id');
        $this->addForeignKey('rel_option_id', 'vote_option', 'option_id', 'option', 'id');
    }

    public function down() {
        $this->dropTable('vote_option');
    }
}

