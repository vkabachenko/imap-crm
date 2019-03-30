<?php

namespace app\models;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $email;
    public $authKey;
    public $accessToken;
   /*
    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];
       */


public static function tableName()
{
    return 'employees';
}

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {

$rows = (new \yii\db\Query())
    ->select(['*'])
    ->from('employees')
    ->where("id='".$id."'")
    ->limit(1)
    ->all();


return new static($rows[0]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {

$rows = (new \yii\db\Query())
    ->select(['*'])
    ->from('employees')
    ->where("email='".$username."'")
    ->limit(1)
    ->all();
    //print_R($rows[0]);
return new static($rows[0]);
/*
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }
*/
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->id;
    }

    public function getRule()
    {
        return $this->rule;
    }
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
    //echo md5($password);
        return $this->pwd === md5($password);
    }
}
