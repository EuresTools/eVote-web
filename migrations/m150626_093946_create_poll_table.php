<?php

use yii\db\Schema;
use yii\db\Migration;

class m150626_093946_create_poll_table extends Migration
{
    public function up() {
        $this->createTable('poll', [
            'id' => Schema::TYPE_PK,
            'question' => Schema::TYPE_TEXT . ' NOT NULL',
            'select_min' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'select_max' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
            'start_time' => Schema::TYPE_DATETIME . ' NOT NULL',
            'end_time' => Schema::TYPE_DATETIME . ' NOT NULL',
            'organizer_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_DATETIME,
            'updated_at' => Schema::TYPE_DATETIME
        ]);

        $this->addForeignKey('poll_organizer_id', 'poll', 'organizer_id', 'organizer', 'id');
    }

    public function down() {
        $this->dropTable('poll');
    }
}

