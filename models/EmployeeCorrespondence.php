<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee_correspondence".
 *
 * @property integer $id
 * @property integer $employee_id
 * @property string $user_imported
 *
 * @property EmployeesAR $employee
 */
class EmployeeCorrespondence extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee_correspondence';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id'], 'integer'],
            [['user_imported'], 'required'],
            [['user_imported'], 'string', 'max' => 255],
            [['user_imported'], 'unique'],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeesAR::className(), 'targetAttribute' => ['employee_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'Employee ID',
            'user_imported' => 'User Imported',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(EmployeesAR::className(), ['id' => 'employee_id']);
    }
}
