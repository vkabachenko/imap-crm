<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sip".
 *
 * @property integer $id
 * @property string $name
 * @property string $num
 *
 * @property SipUser[] $sipUsers
 */
class SipAR extends \yii\db\ActiveRecord
{
    /* @var array */
    public $users = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sip';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'num'], 'required'],
            [['name', 'num'], 'string', 'max' => 255],
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
            'num' => 'Номер',
            'users' => 'Доступ сотрудникам',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSipUsers()
    {
        return $this->hasMany(SipUser::className(), ['sip_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->users = EmployeesAR::find()
            ->select(['employees.id'])
            ->joinWith('sipUsers', false)
            ->where(['sip_user.sip_id' => $this->id])
            ->andWhere(['is_admin' => false])
            ->column();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->users = empty($this->users) ? [] : $this->users;
        $users = array_merge($this->users, EmployeesAR::adminIds());
        SipUser::deleteAll(['sip_id' => $this->id]);
        foreach ($users as $userId) {
            $model = new SipUser([
                'user_id' => $userId,
                'sip_id' => $this->id
            ]);
            $model->save();
        }
    }

}
