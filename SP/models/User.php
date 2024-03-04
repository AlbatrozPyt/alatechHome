<?php

namespace app\models;

use Lcobucci\JWT\Token;
use sizeg\jwt\Jwt;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $accessToken
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'accessToken'], 'required'],
            [['username'], 'string', 'max' => 64],
            [['password', 'accessToken'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'accessToken' => 'Access Token',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            if ($this->isNewRecord)
            {
                $this->password = hash('sha256', $this->password);
                $this->accessToken = Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

    public function validatePassword($password)
    {
        return User::findOne(['password' => $password]);
    }

    public static function findByUsername($username)
    {
        return User::findOne(['username' => $username]);
    }

    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return User::findOne(['accessToken' => $token]);
    }

    public function getId()
    {
        // TODO: Implement getId() method.
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }


    public function generateToken()
    {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];
        $header = json_encode($header);
        $header = base64_encode($header);

        $payload = [
            'iss' => 'SP/AlatechMachines',
            'exp' => time() + 3600*24*30,
            'id' => 'unique-id',
        ];
        $payload = json_encode($payload);
        $payload = (base64_encode($payload));

        $key = 'secret_key';

        $sign = hash_hmac('sha256', "$header.$payload", $key, true);
        $sign = base64_encode($sign);

        return "$header.$payload.$sign";
    }
}

