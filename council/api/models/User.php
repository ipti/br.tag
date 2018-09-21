<?php

namespace app\models;

use yii\mongodb\ActiveRecord;
use MongoDB\BSON\ObjectId;
use Yii;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'user';
    }

    public function attributes()
    {
        return ['_id', 'name', 'email', 'status', 'address', 'credential'];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['_id' => new ObjectId($id)]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['credential.access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['credential.username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
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
        $credential = $this->credential;
        return $credential['password'] === $password;
    }

    public function generateAccessToken($expireInSeconds = 21600)
    {
        $credential = $this->credential;
        $credential['access_token'] = Yii::$app->security->generateRandomString() . '_' . (time() + $expireInSeconds);
        $this->credential = $credential;
        $this->save();
        return $credential['access_token'];
    }

    public function destroyAccessToken()
    {
        $credential = $this->credential;
        $credential['access_token'] = '';
        $this->credential = $credential;
        return $this->save();
    }

    public function isAccessTokenValid()
    {
        $credential = $this->credential;

        if (!empty($credential['access_token'])) {
            $timestamp = (int) substr($credential['access_token'], strrpos($credential['access_token'], '_') + 1);
            return $timestamp > time();
        }
        return false;
    }
}
