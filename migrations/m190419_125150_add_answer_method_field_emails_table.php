<?php

use yii\db\Migration;

class m190419_125150_add_answer_method_field_emails_table extends Migration
{
    public function up()
    {
        $this->addColumn('emails', 'answer_method', "ENUM('mail', 'phone')");
    }

    public function down()
    {
        $this->dropColumn('emails', 'answer_method');
    }

}
