<?php

use yii\db\Schema;
use yii\db\Migration;

class m150626_094153_create_member_table extends Migration
{
    public function up() {
        $this->createTable('member', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'group' => Schema::TYPE_STRING,
            'poll_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_DATETIME,
            'updated_at' => Schema::TYPE_DATETIME
        ]);

        $this->addForeignKey('member_poll_id', 'member', 'poll_id', 'poll', 'id');
    }

    public function down() {
        $this->dropTable('member');
    }
}

