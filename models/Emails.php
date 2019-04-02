<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "emails".
 *
 * @property integer $id
 * @property integer $mailbox_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $comment
 * @property integer $status_id
 * @property integer $is_read
 * @property integer $is_in_work
 * @property integer $manager_id
 * @property string imap_raw_content
 * @property string imap_id
 * @property string imap_date
 * @property string imap_from
 * @property string imap_to
 * @property string imap_subject
 *
 * @property Mails $mailbox
 */
class Emails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'emails';
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => function () {
                    return date('Y-m-d H:i:s');
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mailbox_id'], 'required'],
            [['mailbox_id', 'status_id', 'manager_id'], 'integer'],
            [['imap_raw_content', 'imap_id', 'imap_date', 'imap_from', 'imap_to', 'imap_subject'], 'string'],
            [['is_read', 'is_in_work'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['comment'], 'string', 'max' => 255],
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
            'created_at' => 'В системе',
            'updated_at' => 'Updated At',
            'comment' => 'Комментарий',
            'status_id' => 'Статус',
            'manager_id' => 'Менеджер',
            'is_read' => 'Прочтено',
            'is_in_work' => 'В работе',
            'imap_raw_content' => 'imap_raw_content',
            'imap_id' => 'imap_id',
            'imap_date' => 'Дата письма',
            'imap_from' => 'Oт кого',
            'imap_to' => 'Кому',
            'imap_subject' => 'Тема',
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
    public function getEmailStatus()
    {
        return $this->hasOne(EmailStatus::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(EmployeesAR::className(), ['id' => 'manager_id']);
    }
}
