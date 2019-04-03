<?php

use yii\db\Migration;

class m190403_153923_set_default_is_read_emails_table extends Migration
{
    public function up()
    {
        $this->alterColumn('emails','is_read', $this->boolean()->defaultValue(0));
    }

    public function down()
    {
        $this->alterColumn('emails','is_read', 'boolean');
    }
}
