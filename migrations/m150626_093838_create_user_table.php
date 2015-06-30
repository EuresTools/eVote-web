<?php

use yii\db\Schema;
use yii\db\Migration;

class m150626_093838_create_user_table extends Migration
{
    public function up() {
        $this->createTable('user', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . ' NOT NULL UNIQUE',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING,
            'is_admin' => Schema::TYPE_BOOLEAN . ' DEFAULT FALSE',
            'organizer_id' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_DATETIME,
            'updated_at' => Schema::TYPE_DATETIME
        ]);

        $this->addForeignKey('user_organizer_id', 'user', 'organizer_id', 'organizer', 'id', $delete='SET NULL');
    }

    public function down() {
        $this->dropTable('user');
    }
}

