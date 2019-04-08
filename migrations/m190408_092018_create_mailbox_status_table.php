<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mailbox_status`.
 */
class m190408_092018_create_mailbox_status_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('mailbox_status', [
            'id' => $this->primaryKey(),
            'mailbox_id' => $this->integer()->notNull(),
            'status' => $this->string()->notNull()
        ]);

        $this->createIndex('ind_mailbox_status_mailbox_id', 'mailbox_status', 'mailbox_id');
        $this->addForeignKey(
            'fk_mailbox_status_mailbox_id',
            'mailbox_status',
            'mailbox_id',
            'mails',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_mailbox_status_mailbox_id','mailbox_status');
        $this->dropTable('mailbox_status');
    }

}
