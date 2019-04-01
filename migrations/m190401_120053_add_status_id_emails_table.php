<?php

use yii\db\Migration;

class m190401_120053_add_status_id_emails_table extends Migration
{
    public function up()
    {
        $this->addColumn('emails','status_id', 'integer');

        $this->createIndex('ind_emails_status_id', 'emails', 'status_id');
        $this->addForeignKey(
            'fk_emails_status_id',
            'emails',
            'status_id',
            'email_status',
            'id',
            'SET NULL'
        );


    }

    public function down()
    {
        $this->dropForeignKey('fk_emails_status_id','emails');
        $this->dropColumn('emails', 'status_id');
    }

}
