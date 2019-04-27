<?php

use yii\db\Migration;

class m190426_132514_add_status_field_email_reply_table extends Migration
{
    public function up()
    {
        $this->addColumn('email_reply', 'status', "ENUM('draft', 'deleted')");
    }

    public function down()
    {
        $this->dropColumn('email_reply', 'status');
    }

}
