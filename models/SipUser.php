<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sip_user".
 *
 * @property integer $id
 * @property integer $sip_id
 * @property integer $user_id
 *
 * @property Sip $sip
 * @property Employees $user
 */
class SipUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sip_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sip_id', 'user_id'], 'required'],
            [['sip_id', 'user_id'], 'integer'],
            [['sip_id'], 'exist', 'skipOnError' => true, 'targetClass' => SipAR::className(), 'targetAttribute' => ['sip_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeesAR::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sip_id' => 'Sip ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSip()
    {
        return $this->hasOne(SipAR::className(), ['id' => 'sip_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Employees::className(), ['id' => 'user_id']);
    }
}
