<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

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
     * return array
     */
    public static function emailStatusAsMap()
    {
        $models = self::find()->all();
        $list = ArrayHelper::map($models, 'status', 'status');


        return $list;
    }

    /**
     * return array
     */
    public static function emailStatusAsMapForGrid()
    {
        $list = self::emailStatusAsMap();
        $empty = ['empty' => 'Не задано'];
        return $empty + $list;
    }

}
