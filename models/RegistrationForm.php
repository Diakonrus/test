<?php

namespace app\models;

use Yii;
use yii\base\Model;


class RegistrationForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_confirm;
    public $role;
    public $status;

    const SCENARIO_REGISTRATION = 'default';

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'email', 'password', 'password_confirm'], 'required'],
            [['username', 'email', 'password', 'password_confirm'], 'filter', 'filter' => 'trim'],
            ['password_confirm', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают!', 'on' => self::SCENARIO_REGISTRATION],
            ['username', 'string', 'min'=>3, 'max'=>255],
            ['password', 'string', 'min'=>6, 'max'=>255],
            ['email','email'],
                ['username', 'unique',
                'targetClass' => User::className(),
                'message' => 'Это имя уже занято'
            ],
            ['email', 'unique',
                'targetClass' => User::className(),
                'message' => 'Этот адрес электронной почты уже используется'
            ],
            ['role', 'default', 'value'=>User::ROLE_USER, 'on'=>self::SCENARIO_REGISTRATION],
            ['role', 'in', 'range'=>User::$userRoleList],
            ['status', 'default', 'value'=>User::STATUS_ACTIVE, 'on'=>self::SCENARIO_REGISTRATION],
            ['status', 'in', 'range'=>User::$starusesList],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'email' => 'Email',
            'password' => 'Пароль',
            'password_confirm' => 'Повторите пароль',
        ];
    }

    public function registration(){
        $model = new User();
        $model->username = $this->username;
        $model->email = $this->email;
        $model->setPasswordHash($this->password);
        $model->role = $this->role;
        $model->genAuthKey();
        $model->status = $this->status;
        return $model->saveModel(\app\models\MsgTemplate::CODE_TEMPLATE_NEW_USER) ? $model :  false;

    }

}
