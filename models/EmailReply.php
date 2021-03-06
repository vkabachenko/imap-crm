<?php

namespace app\models;

use app\components\EmailsValidator;
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
 * @property string $status
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
            [['mailbox_id'], 'required'],
            [['mailbox_id', 'reply_to_id', 'manager_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['content'], 'string'],
            [['comment', 'from', 'to', 'subject'], 'string', 'max' => 255],
            ['to', EmailsValidator::className()],
            [['mailbox_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mails::className(), 'targetAttribute' => ['mailbox_id' => 'id']],
            [['manager_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeesAR::className(), 'targetAttribute' => ['manager_id' => 'id']],
            [['reply_to_id'], 'exist', 'skipOnError' => true, 'targetClass' => Emails::className(), 'targetAttribute' => ['reply_to_id' => 'id']],
            ['status', 'in', 'range' => array_keys(self::statuses())],

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

        $uploadPath = UploadFileForm::getUploadPath();

        $scandir = @scandir($uploadPath);
        $uploadedFiles = is_array($scandir) ? array_diff($scandir, ['..', '.']) : [];

        if (!empty($uploadedFiles)) {
            $mailAttachmentPath = $this->setAttachmentPath();

            if (!file_exists($mailAttachmentPath)) {
                mkdir($mailAttachmentPath, 0777);
            } else {
                array_map('unlink', glob($mailAttachmentPath . '*'));
            }

            foreach ($uploadedFiles as $uploadedFile) {
                rename($uploadPath . $uploadedFile, $mailAttachmentPath . $uploadedFile);
            }
        }
        UploadFileForm::clear();
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

        $uploadPath = UploadFileForm::getUploadPath();
        if ($this->status === 'draft') {
            if (!is_dir($uploadPath) || count(scandir($uploadPath)) === 2) {
                $uploadPath = $this->setAttachmentPath();
            }
        }

        $scandir = @scandir($uploadPath);
        $uploadedFiles = is_array($scandir) ? array_diff($scandir, ['..', '.']) : [];

        foreach ($uploadedFiles as $uploadedFile) {
            $message->attach($uploadPath . $uploadedFile, ['fileName' => $uploadedFile]);
        }

        $to = preg_split("/,[\s]*/", $this->to);

        $message->setFrom($this->from)
            ->setTo($to)
            ->setSubject($this->subject)
            ->setTextBody($this->content);

        if (!$message->send()) {
            \Yii::error($logger->dump());
            throw new \Exception('Ошибка при отправке письма');
        }
    }

    public function sendAndSave()
    {
        try {
            $this->send();
            $this->status = null;
            $this->save(false);
            $this->createXml();
            \Yii::$app->session->setFlash('success', 'Письмо успешно отправлено');
            return true;
        } catch (\Exception $e) {
            \Yii::$app->session->setFlash('error', 'Возникла ошибка при отправке письма id=' . $this->id);
            return false;
        }
    }

    public function setAttachmentPath()
    {
        return \Yii::getAlias('@app/attachments')
            . '/'
            . strval($this->mailbox_id)
            . '/'
            . 'reply'
            . strval($this->id)
            . '/';
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

    public static function getThreadMail(Emails $mail)
    {
        if (strpos('Re:', $mail->imap_subject) !== 0) {
            return null;
        }
        $subject = trim(str_replace('Re:', '', $mail->imap_subject));
        $model = self::find()
            ->where(['to' => $mail->imap_from])
            ->andWhere(['<', 'created_at', $mail->imap_date])
            ->andWhere(['like', 'subject', $subject])
            ->orderBy('created_at DESC')
            ->one();
        return $model;
    }

    public static function statuses()
    {
        return ['draft' => 'черновики', 'deleted' => 'удаленные'];
    }

    public function clearAttributes()
    {
        unset($this->id);
        unset($this->created_at);
        unset($this->updated_at);
        $this->comment = null;
        $this->manager_id = \Yii::$app->user->id;
        $this->status = 'draft';
    }
}
