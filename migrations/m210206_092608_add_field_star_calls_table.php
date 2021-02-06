<?php

use yii\db\Migration;

class m210206_092608_add_field_star_calls_table extends Migration
{
    public function up()
    {
        $this->addColumn('calls', 'star', 'string');
    }

    public function down()
    {
        $this->dropColumn('calls', 'star');
    }

}
