<?php

use yii\db\Migration;

class m200923_100437_add_client_field_recent_calls_table extends Migration
{
    public function up()
    {
        $this->addColumn('recent_calls', 'client', 'json');
    }

    public function down()
    {
        $this->dropColumn('recent_calls', 'client');
    }


}
