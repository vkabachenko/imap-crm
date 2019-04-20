<?php

namespace app\models;

use app\services\XmlService;
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
 * @property string $lock_time
 * @property integer $manager_id
 * @property string imap_raw_content
 * @property string imap_id
 * @property string imap_date
 * @property string imap_from
 * @property string imap_to
 * @property string imap_subject
 * @property integer lock_user_id
 * @property string answer_method
 *
 * @property Mails $mailbox
 */
class Emails extends \yii\db\ActiveRecord implements EMailInterface
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
            [['mailbox_id', 'status_id', 'manager_id', 'lock_user_id'], 'integer'],
            [['lock_time', 'imap_raw_content', 'imap_id', 'imap_date', 'imap_from', 'imap_to', 'imap_subject'], 'string'],
            [['is_read'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['comment'], 'string', 'max' => 255],
            [['mailbox_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mails::className(), 'targetAttribute' => ['mailbox_id' => 'id']],
            ['answer_method', 'in', 'range' => array_keys(self::answerMethods())],
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
            'lock_time' => 'lock_time',
            'imap_raw_content' => 'imap_raw_content',
            'imap_id' => 'imap_id',
            'imap_date' => 'Дата письма',
            'imap_from' => 'Oт кого',
            'imap_to' => 'Кому',
            'imap_subject' => 'Тема',
            'lock_user_id' => 'lock_user_id',
            'answer_method' => 'Способ ответа'
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
        return $this->hasOne(MailboxStatus::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(EmployeesAR::className(), ['id' => 'manager_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmailsReply()
    {
        return $this->hasMany(EmailReply::className(), ['reply_to_id' => 'id']);
    }

    public function setAttachmentPath()
    {
        return \Yii::getAlias('@app/attachments')
            . '/'
            . strval($this->mailbox_id)
            . '/'
            . strval($this->imap_id)
            . '/';
    }

    public static function answerMethods()
    {
        return ['mail' => 'Почта', 'phone' => 'Звонок'];
    }

    /**
     * @inheritDoc
     */
    public function beforeValidate()
    {
        if (!$this->answer_method) {
            $this->answer_method = null;
        }

        return parent::beforeValidate();
    }

    public function createXml()
    {
        $in = [
            [
                'tag' => 'ФайлОбмена',
                'attributes' => [
                    'ДатаВыгрузки' => date("dmYHis")
                ],
                'elements' => [
                    [
                        'tag' => 'Письмо',
                        'attributes' => [
                            'Дата' => date("dmYHis", strtotime($this->imap_date)),
                            'ОтКого' => $this->imap_from,
                            'Кому' => $this->imap_to,
                            'Направление' => 'Входящее',
                            'Тема' => $this->imap_subject,
                            'Комментарий' => $this->comment,
                            'Статус' => $this->emailStatus ? $this->emailStatus->status : '',
                            'Связь' => $this->answer_method ? self::answerMethods()[$this->answer_method] : ''
                        ]

                    ]
                ]
            ]
        ];

        $service = new XmlService();
        $service->create($in);
    }
}
