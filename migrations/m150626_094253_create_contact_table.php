<?php

use yii\db\Schema;
use yii\db\Migration;

class m150626_094253_create_contact_table extends Migration
{
    public function up() {
        $this->createTable('contact', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING,
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'member_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_DATETIME,
            'updated_at' => Schema::TYPE_DATETIME
        ]);

        $this->addForeignKey('contact_member_id', 'contact', 'member_id', 'member', 'id');
    }

    public function down() {
        $this->dropTable('contact');
    }
}

