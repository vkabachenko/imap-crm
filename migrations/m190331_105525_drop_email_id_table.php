<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `email_id`.
 */
class m190331_105525_drop_email_id_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropTable('email_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->createTable('email_id', [
            'id' => $this->primaryKey(),
            'e_id' => $this->string()->notNull()
        ]);
    }
}
