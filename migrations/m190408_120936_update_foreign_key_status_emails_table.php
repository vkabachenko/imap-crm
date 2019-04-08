<?php

use yii\db\Migration;

class m190408_120936_update_foreign_key_status_emails_table extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_emails_status_id','emails');
        $this->addForeignKey(
            'fk_emails_status_id',
            'emails',
            'status_id',
            'mailbox_status',
            'id',
            'SET NULL'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk_emails_status_id','emails');
        $this->addForeignKey(
            'fk_emails_status_id',
            'emails',
            'status_id',
            'email_status',
            'id',
            'SET NULL'
        );
    }

}
