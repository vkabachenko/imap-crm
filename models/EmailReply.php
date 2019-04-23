<?php

namespace app\models;

use app\services\XmlService;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "email_reply".
 *
 * @property integer $id
 * @property integer $mailbox_id
 * @property integer $reply_to_id
 * @property integer $manager_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $comment
 * @property string $from
 * @property string $to
 * @property string $subject
 * @property string $content
 *
 * @property Mails $mailbox
 * @property Employees $manager
 * @property Emails $replyTo
 */
class EmailReply extends \yii\db\ActiveRecord implements EMailInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_reply';
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
            [['mailbox_id', 'reply_to_id'], 'required'],
            [['mailbox_id', 'reply_to_id', 'manager_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['content'], 'string'],
            [['comment', 'from', 'to', 'subject'], 'string', 'max' => 255],
            [['mailbox_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mails::className(), 'targetAttribute' => ['mailbox_id' => 'id']],
            [['manager_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeesAR::className(), 'targetAttribute' => ['manager_id' => 'id']],
            [['reply_to_id'], 'exist', 'skipOnError' => true, 'targetClass' => Emails::className(), 'targetAttribute' => ['reply_to_id' => 'id']],
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
            'reply_to_id' => 'Reply To ID',
            'manager_id' => 'Менеджер',
            'created_at' => 'Дата создания',
            'updated_at' => 'Updated At',
            'comment' => 'Комментарий',
            'from' => 'От кого',
            'to' => 'Кому',
            'subject' => 'Тема',
            'content' => 'Содержание',
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
    public function getManager()
    {
        return $this->hasOne(EmployeesAR::className(), ['id' => 'manager_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReplyTo()
    {
        return $this->hasOne(Emails::className(), ['id' => 'reply_to_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->createXml();

        $uploadPath = \Yii::getAlias('@app/uploads/');
        $uploadedFiles = array_diff(scandir($uploadPath), ['..', '.']);

        if (!empty($uploadedFiles)) {
            $mailAttachmentPath = $this->setAttachmentPath();

            if (!file_exists($mailAttachmentPath)) {
                mkdir($mailAttachmentPath, 0777);
            }

            foreach ($uploadedFiles as $uploadedFile) {
                rename($uploadPath . $uploadedFile, $mailAttachmentPath . $uploadedFile);
            }
        }
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function send()
    {
        /* @var $mailbox Mails */
        $mailbox = $this->mailbox;

        \Yii::$app->set('mailer', [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mail.ru',
                'username' => $mailbox->login,
                'password' => $mailbox->pwd,
                'port' => '465',
                'encryption' => 'ssl',
            ],
         ]);

        /* @var $mailer \yii\swiftmailer\Mailer */
        $mailer = \Yii::$app->mailer;
        /* @var $transport \Swift_Transport */

        $logger = new \Swift_Plugins_Loggers_ArrayLogger();
        $mailer->getSwiftMailer()->registerPlugin(new \Swift_Plugins_LoggerPlugin($logger));

        $message = $mailer->compose();

        $uploadPath = \Yii::getAlias('@app/uploads/');
        $uploadedFiles = array_diff(scandir($uploadPath), ['..', '.']);

        foreach ($uploadedFiles as $uploadedFile) {
            $message->attach($uploadPath . $uploadedFile);
        }

        $message->setFrom($this->from)
            ->setTo($this->to)
            ->setSubject($this->subject)
            ->setTextBody($this->content);

        if (!$message->send()) {
            \Yii::error($logger->dump());
        }
    }

    public function setAttachmentPath()
    {
        return \Yii::getAlias('@app/attachments')
            . '/'
            . strval($this->mailbox_id)
            . '/'
            . strval($this->replyTo->imap_id)
            . 'reply'
            . strval($this->id)
            . '/';
    }

    private function createXml()
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
                                    'Дата' => date("dmYHis", strtotime($this->created_at)),
                                    'ОтКого' => $this->from,
                                    'Кому' => $this->to,
                                    'Направление' => 'Исходящее',
                                    'Тема' => $this->subject,
                                    'Комментарий' => $this->comment,
                                    'Статус' => '',
                                    'Связь' => ''
                                 ]

                            ]
                        ]
                  ]
              ];

        $service = \Yii::createObject(XmlService::className());
        $service->create($in);
    }
}
