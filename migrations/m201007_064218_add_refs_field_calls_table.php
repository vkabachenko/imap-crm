<?php

use yii\db\Migration;

class m201007_064218_add_refs_field_calls_table extends Migration
{
    public function up()
    {
        $this->addColumn('calls', 'refs', 'json');
    }

    public function down()
    {
        $this->dropColumn('calls', 'refs');
    }

}
