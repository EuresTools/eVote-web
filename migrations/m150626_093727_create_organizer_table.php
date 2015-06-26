<?php

use yii\db\Schema;
use yii\db\Migration;

class m150626_093727_create_organizer_table extends Migration
{
    public function up() {
        $this->createTable('organizer', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL UNIQUE',
            'created_at' => Schema::TYPE_DATETIME,
            'updated_at' => Schema::TYPE_DATETIME
        ]);
    }


    public function down() {
        $this->dropTable('organizer');
    }
}

