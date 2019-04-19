<?php

use yii\db\Migration;

class m190419_083329_add_signature_field_employees_table extends Migration
{
    public function up()
    {
        $this->addColumn('employees', 'mail_signature', 'string');
    }

    public function down()
    {
        $this->dropColumn('employees', 'mail_signature');
    }
}
