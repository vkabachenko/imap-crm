<?php

use yii\db\Migration;

class m190417_130230_add_default_mailbox_id_employee_table extends Migration
{
    public function up()
    {
        $this->addColumn( 'employees', 'default_mailbox_id','integer');
        $this->createIndex('ind_employees_default_mailbox_id', 'employees', 'default_mailbox_id');
        $this->addForeignKey(
            'fk_employees_default_mailbox_id',
            'employees',
            'default_mailbox_id',
            'mails',
            'id',
            'SET NULL'
        );

    }

    public function down()
    {
        $this->dropForeignKey('fk_employees_default_mailbox_id', 'employees');
        $this->dropColumn('employees', 'default_mailbox_id');
    }

}
