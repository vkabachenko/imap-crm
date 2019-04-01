<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employees".
 *
 * @property integer $id
 * @property string $name
 * @property string $tel
 * @property string $email
 * @property string $rule
 * @property integer $date
 * @property string $pwd
 * @property string $guid
 *
 * @property Emails[] $emails
 * @property MailboxUser[] $mailboxUsers
 */
class EmployeesAR extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employees';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'tel', 'email', 'rule', 'date', 'pwd', 'guid'], 'required'],
            [['date'], 'integer'],
            [['name', 'tel', 'email', 'rule', 'pwd', 'guid'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'tel' => 'Tel',
            'email' => 'Email',
            'rule' => 'Rule',
            'date' => 'Date',
            'pwd' => 'Pwd',
            'guid' => 'Guid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmails()
    {
        return $this->hasMany(Emails::className(), ['manager_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailboxUsers()
    {
        return $this->hasMany(MailboxUser::className(), ['user_id' => 'id']);
    }
}
