<?php

use yii\db\Migration;

class m190401_123556_add_check_fields_emails_table extends Migration
{
    public function up()
    {
        $this->addColumn('emails', 'is_read', 'boolean');
        $this->addColumn('emails', 'manager_id', 'integer');
        $this->addColumn('emails', 'is_in_work', 'boolean');

        $this->createIndex('ind_emails_manager_id', 'emails', 'manager_id');
        $this->addForeignKey(
            'fk_emails_manager_id',
            'emails',
            'manager_id',
            'employees',
            'id',
            'SET NULL'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk_emails_manager_id', 'emails');
        $this->dropColumn('emails', 'manager_id');
        $this->dropColumn('emails', 'is_read');
        $this->dropColumn('emails', 'is_in_work');
    }

}
