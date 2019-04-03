<?php

use yii\db\Migration;

class m190403_064418_change_imap_raw_content_type_emails_table extends Migration
{
    public function up()
    {
        $this->alterColumn('emails', 'imap_raw_content', 'LONGTEXT');
    }

    public function down()
    {
        $this->alterColumn('emails', 'imap_raw_content', 'text');
    }
}
