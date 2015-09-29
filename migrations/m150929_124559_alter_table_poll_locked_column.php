<?php

use yii\db\Schema;
use yii\db\Migration;

class m150929_124559_alter_table_poll_locked_column extends Migration
{
    public function up()
    {
        $this->alterColumn('poll', 'locked', Schema::TYPE_INTEGER .' not null');

    }

    public function down()
    {
        $this->alterColumn('poll', 'locked', Schema::TYPE_INTEGER);
    }
}
