<?php

use yii\db\Migration;

class m190402_071010_add_content_fields_email_table extends Migration
{
    public function up()
    {
        $this->addColumn('emails', 'imap_raw_content', 'text');
        $this->addColumn('emails', 'imap_id', 'string');
        $this->addColumn('emails', 'imap_date', 'string');
        $this->addColumn('emails', 'imap_from', 'string');
        $this->addColumn('emails', 'imap_to', 'string');
        $this->addColumn('emails', 'imap_subject', 'string');
    }

    public function down()
    {
        $this->dropColumn('emails', 'imap_raw_content');
        $this->dropColumn('emails', 'imap_id');
        $this->dropColumn('emails', 'imap_date');
        $this->dropColumn('emails', 'imap_from');
        $this->dropColumn('emails', 'imap_to');
        $this->dropColumn('emails', 'imap_subject');
    }

}
