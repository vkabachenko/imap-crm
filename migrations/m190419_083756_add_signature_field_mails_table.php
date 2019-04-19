<?php

use yii\db\Migration;

class m190419_083756_add_signature_field_mails_table extends Migration
{
    public function up()
    {
        $this->addColumn('mails', 'signature', 'string');
    }

    public function down()
    {
        $this->dropColumn('mails', 'signature');
    }

 }
