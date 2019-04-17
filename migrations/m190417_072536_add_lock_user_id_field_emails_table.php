<?php

use yii\db\Migration;

class m190417_072536_add_lock_user_id_field_emails_table extends Migration
{
    public function up()
    {
        $this->addColumn('emails', 'lock_user_id', 'integer');
        $this->createIndex('ind_emails_lock_user_id', 'emails', 'lock_user_id');
        $this->addForeignKey(
            'fk_emails_lock_user_id',
            'emails',
            'lock_user_id',
            'employees',
            'id',
            'SET NULL'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk_emails_lock_user_id', 'emails');
        $this->dropColumn('emails', 'lock_user_id');
    }

}
