<?php

use yii\db\Schema;
use yii\db\Migration;

class m150626_094357_create_code_table extends Migration
{
    public function up() {
        $this->createTable('code', [
            'id' => Schema::TYPE_PK,
            'token' => Schema::TYPE_STRING . ' NOT NULL UNIQUE',
            'poll_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'member_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'is_valid' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
            'created_at' => Schema::TYPE_DATETIME,
            'updated_at' => Schema::TYPE_DATETIME
        ]);

        $this->addForeignKey('code_poll_id', 'code', 'poll_id', 'poll', 'id');
        $this->addForeignKey('code_member_id', 'code', 'member_id', 'member', 'id');
    }

    public function down() {
        $this->dropTable('code');
    }
}

