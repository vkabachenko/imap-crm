<?php

use yii\db\Migration;

/**
 * Handles the creation of table `email_status`.
 */
class m190401_115628_create_email_status_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('email_status', [
            'id' => $this->primaryKey(),
            'status' => $this->string()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('email_status');
    }
}
