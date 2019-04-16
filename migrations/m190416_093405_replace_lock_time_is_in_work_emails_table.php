<?php

use yii\db\Migration;

class m190416_093405_replace_lock_time_is_in_work_emails_table extends Migration
{
    public function up()
    {
        $this->dropColumn('emails', 'is_in_work');
        $this->addColumn('emails', 'lock_time', $this->dateTime());

    }

    public function down()
    {
        $this->dropColumn('emails', 'lock_time');
        $this->addColumn('emails', 'is_in_work', 'boolean');
    }

}
