<?php

use yii\db\Migration;

/**
 * Handles the creation of table `email_reply`.
 */
class m190410_082625_create_email_reply_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('email_reply', [
            'id' => $this->primaryKey(),
            'mailbox_id' => $this->integer()->notNull(),
            'reply_to_id' => $this->integer()->notNull(),
            'manager_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'comment' => $this->string(),
            'from' => $this->string(),
            'to' => $this->string(),
            'subject' => $this->string(),
            'content' => 'LONGTEXT'
        ]);

        $this->createIndex('ind_email_reply_mailbox_id', 'email_reply', 'mailbox_id');
        $this->createIndex('ind_email_reply_reply_to_id', 'email_reply', 'reply_to_id');
        $this->createIndex('ind_email_reply_id', 'email_reply', 'manager_id');

        $this->addForeignKey(
            'fk_email_reply_manager_id',
            'email_reply',
            'manager_id',
            'employees',
            'id',
            'SET NULL'
        );
        $this->addForeignKey(
            'fk_email_reply_reply_to_id',
            'email_reply',
            'reply_to_id',
            'emails',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_email_reply_mailbox_id',
            'email_reply',
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
        $this->dropForeignKey('fk_email_reply_mailbox_id', 'email_reply');
        $this->dropForeignKey('fk_email_reply_reply_to_id', 'email_reply');
        $this->dropForeignKey('fk_email_reply_manager_id', 'email_reply');
        $this->dropTable('email_reply');
    }
}
