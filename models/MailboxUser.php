<?php

namespace app\models;

use common\models\Project;
use Yii;

/**
 * This is the model class for table "mailbox_user".
 *
 * @property integer $id
 * @property integer $mailbox_id
 * @property integer $user_id
 *
 * @property Mails $mailbox
 * @property Employees $user
 */
class MailboxUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mailbox_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mailbox_id', 'user_id'], 'required'],
            [['mailbox_id', 'user_id'], 'integer'],
            [['mailbox_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mails::className(), 'targetAttribute' => ['mailbox_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employees::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mailbox_id' => 'Mailbox ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailbox()
    {
        return $this->hasOne(Mails::className(), ['id' => 'mailbox_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Employees::className(), ['id' => 'user_id']);
    }

}
