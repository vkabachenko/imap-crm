<?php

use yii\db\Migration;

/**
 * Handles the creation of table `emails`.
 */
class m190331_105759_create_emails_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('emails', [
            'id' => $this->primaryKey(),
            'mailbox_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'comment' => $this->string()
        ]);

        $this->createIndex('ind_emails_mailbox_id', 'emails', 'mailbox_id');

        $this->addForeignKey(
            'fk_emails_mailbox_id',
            'emails',
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
        $this->dropForeignKey('fk_emails_mailbox_id', 'emails');
        $this->dropTable('emails');
    }
}
