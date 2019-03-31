<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mailbox_user`.
 */
class m190331_085352_create_mailbox_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('mailbox_user', [
            'id' => $this->primaryKey(),
            'mailbox_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull()
        ]);

        $this->createIndex('ind_mailbox_user_mailbox_id', 'mailbox_user', 'mailbox_id');
        $this->createIndex('ind_mailbox_user_user_id', 'mailbox_user', 'user_id');

        $this->addForeignKey(
            'fk_mailbox_user_mailbox_id',
            'mailbox_user',
            'mailbox_id',
            'mails',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_mailbox_user_user_id',
            'mailbox_user',
            'user_id',
            'employees',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_mailbox_user_mailbox_id','mailbox_user');
        $this->dropForeignKey('fk_mailbox_user_user_id','mailbox_user');
        $this->dropTable('mailbox_user');
    }
}
