<?php

use yii\db\Migration;

class m190416_063208_add_start_date_field_mails_table extends Migration
{
    public function up()
    {
        $this->addColumn('mails', 'start_date', $this->date());
    }

    public function down()
    {
        $this->dropColumn('mails', 'start_date');
    }

}
