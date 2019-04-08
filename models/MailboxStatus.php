<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mailbox_status".
 *
 * @property integer $id
 * @property integer $mailbox_id
 * @property string $status
 *
 * @property Mails $mailbox
 */
class MailboxStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mailbox_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mailbox_id', 'status'], 'required'],
            [['mailbox_id'], 'integer'],
            [['status'], 'string', 'max' => 255],
            [['mailbox_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mails::className(), 'targetAttribute' => ['mailbox_id' => 'id']],
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
            'status' => 'Статус',
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
     * return array
     */
    public static function emailStatusAsMap($mailboxId)
    {
        $list = self::find()
            ->select(['status', 'id'])
            ->where(['mailbox_id' => $mailboxId])
            ->orderBy('status')
            ->indexBy('id')
            ->column();
        return $list;
    }

}
