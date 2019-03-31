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
 */
class Mails extends \yii\db\ActiveRecord
{
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
            [['name', 'server', 'login', 'pwd', 'comment'], 'string', 'max' => 255],
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
            'server' => 'Server',
            'login' => 'Login',
            'pwd' => 'Pwd',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailboxUser()
    {
        return $this->hasMany(MailboxUser::className(), ['mailbox_id' => 'id']);
    }
}
