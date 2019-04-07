<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "email_status".
 *
 * @property integer $id
 * @property string $status
 *
 * @property Emails[] $emails
 */
class EmailStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Статус',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmails()
    {
        return $this->hasMany(Emails::className(), ['status_id' => 'id']);
    }

    /**
     * return array
     */
    public static function emailStatusAsMap()
    {
        $list = self::find()
            ->select(['status', 'id'])
            ->orderBy('status')
            ->indexBy('id')
            ->column();
        return $list;
    }
}
