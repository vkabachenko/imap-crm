<?php

use yii\db\Migration;

class m190514_112556_set_nullable_reply_to_id_field_email_reply_table extends Migration
{
    public function up()
    {
         $this->alterColumn('email_reply', 'reply_to_id', $this->integer());

    }

    public function down()
    {
        $this->alterColumn('email_reply', 'reply_to_id', $this->integer()->notNull());
    }

}
