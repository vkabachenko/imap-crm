<?php

use yii\db\Migration;

class m190331_062154_add_comment_mails_table extends Migration
{
    public function up()
    {
        $this->addColumn('mails', 'comment', 'string');
    }

    public function down()
    {
        $this->dropColumn('mails', 'comment');
    }
}
