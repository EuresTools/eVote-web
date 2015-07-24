<?php

use yii\db\Schema;
use yii\db\Migration;

class m150724_080256_create_failed_attempt_table extends Migration
{
    public function up() {
        $this->createTable('failed_attempt', [
            'id' => Schema::TYPE_PK,
            'ip_address' => Schema::TYPE_STRING . ' NOT NULL',
            'token' => Schema::TYPE_STRING,
            'time' => Schema::TYPE_DATETIME
        ]);

    }

    public function down()
    {
        $this->dropTable('failed_attempt');
        return true;
    }
}
