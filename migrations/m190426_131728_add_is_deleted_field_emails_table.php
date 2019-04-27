<?php

use yii\db\Migration;

class m190426_131728_add_is_deleted_field_emails_table extends Migration
{
    public function up()
    {
        $this->addColumn('emails', 'is_deleted', 'boolean');
    }

    public function down()
    {
        $this->dropColumn('emails', 'is_deleted');
    }
}
