<?php

use yii\db\Schema;
use yii\db\Migration;

class m150626_094437_create_vote_table extends Migration
{
    public function up() {
        $this->createTable('vote', [
            'id' => Schema::TYPE_PK,
            'code_id' => Schema::TYPE_INTEGER . ' NOT NULL UNIQUE',
            'created_at' => Schema::TYPE_DATETIME,
            'updated_at' => Schema::TYPE_DATETIME
        ]);

        $this->addForeignKey('vote_code_id', 'vote', 'code_id', 'code', 'id');
    }

    public function down() {
        $this->dropTable('vote');
    }
}

