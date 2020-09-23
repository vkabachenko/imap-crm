<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recent_calls".
 *
 * @property integer $id
 * @property string $sid
 * @property string $tel_from
 * @property string $tel_to
 * @property string $date
 * @property string $sip
 * @property string $status
 * @property array|null $client
 */
class RecentCalls extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'recent_calls';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'tel_from', 'tel_to', 'status'], 'required'],
            [['date'], 'safe'],
            [['sid', 'tel_from', 'tel_to', 'sip', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sid' => 'Sid',
            'tel_from' => 'Tel From',
            'tel_to' => 'Tel To',
            'date' => 'Date',
            'sip' => 'Sip',
            'status' => 'Status',
        ];
    }

    public static function getByPhoneFrom($phone)
    {
        return self::find()->where(['tel_from' => $phone])->one();
    }
}
