<?php

use yii\db\Migration;

class m190406_084547_add_is_admin_employee_table extends Migration
{
    public function up()
    {
        $this->addColumn('employees', 'is_admin', $this->boolean()->defaultValue(false));

    }

    public function down()
    {
        $this->dropColumn('employees', 'is_admin');
    }

}
