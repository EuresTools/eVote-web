<?php

use yii\db\Schema;
use yii\db\Migration;

class m150626_094106_create_option_table extends Migration
{
    public function up() {
        $this->createTable('option', [
            'id' => Schema::TYPE_PK,
            'text' => Schema::TYPE_STRING . ' NOT NULL',
            'poll_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_DATETIME,
            'updated_at' => Schema::TYPE_DATETIME
        ]);

        $this->addForeignKey('option_poll_id', 'option', 'poll_id', 'poll', 'id');
    }

    public function down() {
        $this->dropTable('option');
    }
}

