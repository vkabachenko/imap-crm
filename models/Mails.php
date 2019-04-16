<?php

namespace app\models;

use Yii;


/**
 * This is the model class for table "mails".
 *
 * @property integer $id
 * @property string $name
 * @property string $server
 * @property string $login
 * @property string $pwd
 * @property string $comment
 * @property string $start_date
 */
class Mails extends \yii\db\ActiveRecord
{
    /* @var array */
    public $users = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mails';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'server', 'login', 'pwd'], 'required'],
            [['name', 'server', 'login', 'pwd', 'comment', 'start_date'], 'string', 'max' => 255],
            [['users'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'server' => 'Imap сервер',
            'login' => 'E-mail',
            'pwd' => 'Пароль',
            'comment' => 'Комментарий',
            'users' => 'Доступ сотрудникам',
            'start_date' => 'Дата начала скачивания'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailboxUser()
    {
        return $this->hasMany(MailboxUser::className(), ['mailbox_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEMails()
    {
        return $this->hasMany(EMails::className(), ['mailbox_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailboxStatuses()
    {
        return $this->hasMany(MailboxStatus::className(), ['mailbox_id' => 'id']);
    }


    public function beforeValidate()
    {
        if (empty($this->start_date)) {
            $this->start_date = date('Y-m-d');
        } elseif (strpos($this->start_date, '/') !== false) {
            $this->start_date = date('Y-m-d', strtotime($this->start_date));
        }
        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->users = EmployeesAR::find()
            ->select(['employees.id'])
            ->joinWith('mailboxUsers', false)
            ->where(['mailbox_user.mailbox_id' => $this->id])
            ->andWhere(['is_admin' => false])
            ->column();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->users = empty($this->users) ? [] : $this->users;
        $users = array_merge($this->users, EmployeesAR::adminIds());
        MailboxUser::deleteAll(['mailbox_id' => $this->id]);
        foreach ($users as $userId) {
            $model = new MailboxUser([
                'user_id' => $userId,
                'mailbox_id' => $this->id
            ]);
            $model->save();
        }
    }
}
