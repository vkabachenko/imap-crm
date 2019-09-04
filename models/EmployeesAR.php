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
 * @property boolean $is_admin
 * @property integer $default_mailbox_id
 * @property string $mail_signature
 *
 * @property Emails[] $emails
 * @property MailboxUser[] $mailboxUsers
 * @property SipUser[] $sipUsers
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
            [['date', 'default_mailbox_id'], 'integer'],
            [['name', 'tel', 'email', 'rule', 'pwd', 'guid', 'mail_signature'], 'string', 'max' => 255],
            [['is_admin'], 'boolean']
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
            'is_admin' => 'Администратор',
            'default_mailbox_id' => 'Почтовый ящик по умолчанию',
            'mail_signature' => 'Подпись в письме'
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSipUsers()
    {
        return $this->hasMany(SipUser::className(), ['user_id' => 'id']);
    }

    /**
     * return array
     */
    public static function usersAsMap()
    {
        $list = self::find()
            ->select(['name', 'id'])
            ->orderBy('name')
            ->indexBy('id')
            ->column();
        return $list;
    }


    public static function usersAsMapForGrid()
    {
        $list = self::usersAsMap();
        $empty = ['empty' => 'Не задано'];
        return $empty + $list;
    }

    /**
     * return array
     */
    public static function notAdminsAsMap()
    {
        $list = self::find()
            ->select(['name', 'id'])
            ->where(['is_admin' => false])
            ->orderBy('name')
            ->indexBy('id')
            ->column();
        return $list;
    }

    public static function adminIds()
    {
        return self::find()
            ->select(['id'])
            ->where(['is_admin' => true])
            ->column();
    }
}
