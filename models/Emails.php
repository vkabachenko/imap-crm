<?php

namespace app\models;

use app\helpers\ConvertLinks;
use app\services\XmlService;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;


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
 * @property boolean is_deleted
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
            [['is_read', 'is_deleted'], 'boolean'],
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
            'mailbox_id' => 'Ящик',
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
            'answer_method' => 'Способ ответа',
            'is_deleted' => 'Удалено',
        ];
    }

    public function getFullTime()
    {
        $content = Json::decode($this->imap_raw_content);

        return $content['headers']['date'];
    }

    public function getContent()
    {
        $content = Json::decode($this->imap_raw_content);
        $textPlain = $content['textPlain'];
        $textHtml = $content['textHtml'];
        $textEmail = empty($textHtml) ? nl2br($textPlain) : $textHtml;
        $textEmail = ConvertLinks::convert($textEmail);

        return $textEmail;
    }

    public function getContentForReply()
    {
        $content = Json::decode($this->imap_raw_content);
        $textPlain = $content['textPlain'];
        $textHtml = $content['textHtml'];
        $textEmail = empty($textHtml) ? $textPlain : self::convertHtml2Text($textHtml);

        $divider = "\n\n---------------------\n\n";
        $senderData = 'Исходное сообщение от ' . $this->imap_from . ' получено ' . $this->getFullTime() . "\n\n";

        $signature = \Yii::$app->user->identity->mail_signature . "\n" . $this->mailbox->signature;


        return $divider . $senderData . $textEmail . $divider . $signature;
    }

    public function getContentForForward()
    {
        $content = Json::decode($this->imap_raw_content);
        $textPlain = $content['textPlain'];
        $textHtml = $content['textHtml'];
        $textEmail = empty($textHtml) ? $textPlain : self::convertHtml2Text($textHtml);

        $divider = "\n\n---------------------\n\n";
        $senderData = 'Пересланное сообщение от ' . $this->imap_from . ' получено ' . $this->getFullTime() . "\n\n";

        return $divider . $senderData . $textEmail . $divider;
    }

    public static function convertHtml2Text($html)
    {
        $rules = [
                    '<div>' => "\n",
                    '</div>' => "\n",
                    '<p>' => "\n",
                    '</p>' => "\n",
                    '<br>' => "\n",
                    '<br/>' => "\n",
                 ];

        $text = strtr($html, $rules);
        return strip_tags($text);
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
    public function getLockUser()
    {
        return $this->hasOne(EmployeesAR::className(), ['id' => 'lock_user_id']);
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

    public static function answerMethodsForGrid()
    {
        return ['empty' => 'Не задано'] + self::answerMethods();
    }

    public function setAnswerMethod($answerMethod)
    {
        if (array_key_exists($answerMethod, self::answerMethods()) || is_null($answerMethod)) {
            $this->answer_method = $answerMethod;
        }
    }

    public function setStatus($status, $ifNotAlreadySet = true)
    {
        if ($this->status_id && $ifNotAlreadySet) {
            return;
        }

        $statusModel = MailboxStatus::find()->where(['mailbox_id' => $this->mailbox_id, 'status' => $status])->one();

        if (is_null($statusModel)) {
            return;
        }

        $this->status_id = $statusModel->id;
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

        $service = \Yii::createObject(XmlService::className());;
        $service->create($in);
    }
}
