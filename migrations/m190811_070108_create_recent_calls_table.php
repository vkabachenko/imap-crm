<?php

use yii\db\Migration;

/**
 * Handles the creation of table `recent_calls`.
 */
class m190811_070108_create_recent_calls_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('recent_calls', [
            'id' => $this->primaryKey(),
            'sid' => $this->string()->notNull(),
            'tel_from' => $this->string()->notNull(),
            'tel_to' => $this->string()->notNull(),
            'date' => $this->dateTime(),
            'sip' => $this->string(),
            'status' => $this->string()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('recent_calls');
    }
}
