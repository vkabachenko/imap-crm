<?php

use yii\db\Migration;

/**
 * Handles the creation of table `sip_user`.
 */
class m190904_060903_create_sip_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('sip_user', [
            'id' => $this->primaryKey(),
            'sip_id' => $this->bigInteger()->notNull(),
            'user_id' => $this->integer()->notNull()
        ]);

        $this->createIndex('ind_sip_user_sip_id', 'sip_user', 'sip_id');
        $this->createIndex('ind_sip_user_user_id', 'sip_user', 'user_id');

        $this->addForeignKey(
            'fk_sip_user_sip_id',
            'sip_user',
            'sip_id',
            'sip',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_sip_user_user_id',
            'sip_user',
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
        $this->dropTable('sip_user');
    }
}
