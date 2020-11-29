<?php

use yii\db\Migration;

/**
 * Handles the creation of table `employee_correspondence`.
 */
class m201127_103216_create_employee_correspondence_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('employee_correspondence', [
            'id' => $this->primaryKey(),
            'employee_id' => $this->integer(),
            'user_imported' => $this->string()->notNull()
        ]);
        $this->createIndex(
            'ind_employee_correspondence_user_imported',
        'employee_correspondence',
           'user_imported',
           true
        );
        $this->addForeignKey(
            'fk_employee_correspondence_employee_id_employee_id',
            'employee_correspondence',
            'employee_id',
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
        $this->dropForeignKey(
            'fk_employee_correspondence_employee_id_employee_id',
            'employee_correspondence'
        );
        $this->dropIndex(
            'ind_employee_correspondence_user_imported',
            'employee_correspondence'
        );
        $this->dropTable('employee_correspondence');
    }
}
