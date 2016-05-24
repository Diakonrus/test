<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $email;
    public $rememberMe = true;
    public $status;

    private $auth_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required', 'on'=>'default'],
            ['email','email'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePass'],
        ];
    }

    public function validatePass($attribute){
        if (!$this->hasErrors()){
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password) ){
                $this->addError($attribute, 'Неверный логин или пароль!');
            }

        }
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня',
        ];
    }

    public function getUser(){
        if ($this->auth_user === false){
            $this->auth_user = User::returnUserByUserName($this->username);
        }
        return $this->auth_user;
    }

    public function login(){
        if ($this->validate() &&  $user = $this->getUser()){
            return Yii::$app->user->login($user, (($this->rememberMe)?(3600*24*30):(0))); //Если Запомнить меня - сейвим на 30 дней
        }
        return false;
    }

}
