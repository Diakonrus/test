<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $role
 * @property integer $status
 * @property string $auth_key
 * @property integer $updated_at
 * @property integer $created_at
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{

    //Роли пользователей
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLE_GUEST = 'guest';
    public static $userRoleList = [
        self::ROLE_ADMIN,
        self::ROLE_USER,
        self::ROLE_GUEST
    ];
    public  static $userRoleName = [
        self::ROLE_ADMIN => 'Администратор',
        self::ROLE_USER => 'Пользователь',
    ];


    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public static $starusesList = [
        self::STATUS_NOT_ACTIVE,
        self::STATUS_ACTIVE,
    ];
    public static $statusesName = [
        self::STATUS_NOT_ACTIVE => 'Не активен',
        self::STATUS_ACTIVE => 'Активен',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'role', 'auth_key'], 'required'],
            [['status', 'updated_at', 'created_at'], 'integer'],
            [['username', 'email', 'password'], 'string', 'max' => 255],
            [['auth_key', 'role'], 'string', 'max' => 32],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя пользователя',
            'email' => 'Email',
            'password' => 'Пароль',
            'role' => 'Роль',
            'status' => 'Статус',
            'auth_key' => 'Auth Key',
            'updated_at' => 'Updated At',
            'created_at' => 'Дата создания',
        ];
    }


    public function behaviors(){
        return [
            TimestampBehavior::className(),
        ];
    }

    public static function returnUserByUserName($username){
        return User::findOne([
            'username' => $username,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    public static function returnUserRole($id){

        if ($model = User::findOne($id)){
            return $model->role;
        }
        return false;

    }

    public function saveModel($code){
        if ($this->save()){
            $this->trigger(SendingMsgLog::addMsgLog($code, $this));
            return $this;
        }
        return false;
    }


    /** Хелперы */

    public function setPasswordHash($password){
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function genAuthKey(){
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function validatePassword($password){
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /** Аутентификация */

    public static function findIdentity($id)
    {
        return static::findOne([
            'id' => $id,
            'status' => self::STATUS_ACTIVE
        ]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }


}
