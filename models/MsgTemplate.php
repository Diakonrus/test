<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\models\MsgTemplateType;
use app\models\MsgTemplateUser;
use app\models\User;


/**
 * This is the model class for table "msg_template".
 *
 * @property integer $id
 * @property integer $code
 * @property string $name
 * @property string $title
 * @property string $body
 * @property integer $user_id
 * @property integer $updated_at
 * @property integer $created_at
 *
 * @property User $user
 * @property MsgTemplateUser[] $msgTemplateUsers
 */
class MsgTemplate extends \yii\db\ActiveRecord
{
    //Типы уведомления
    const TYPE_MSG_EMAIL = 1;
    const TYPE_MSG_BROWSEER = 2;
    public static $typeList = [
        self::TYPE_MSG_EMAIL,
        self::TYPE_MSG_BROWSEER
    ];
    public static $typeNames = [
        self::TYPE_MSG_EMAIL => 'Email',
        self::TYPE_MSG_BROWSEER => 'Browseer',
    ];

    //Коды шаблона
    const CODE_TEMPLATE_SIMPLE = 1;
    const CODE_TEMPLATE_NEW_USER = 2;
    const CODE_TEMPLATE_DISABLE_USER = 3;
    const CODE_TEMPLATE_ACTIVE_USER = 4;
    const CODE_TEMPLATE_NEW_POST = 6;
    const CODE_TEMPLATE_UPDATE_POST = 7;
    public static $codeTemplateList = [
        self::CODE_TEMPLATE_SIMPLE,
        self::CODE_TEMPLATE_NEW_USER,
        self::CODE_TEMPLATE_DISABLE_USER,
        self::CODE_TEMPLATE_ACTIVE_USER,
        self::CODE_TEMPLATE_NEW_POST,
        self::CODE_TEMPLATE_UPDATE_POST
    ];

    public static $codeTemplateNames = [
        self::CODE_TEMPLATE_SIMPLE => 'Простое сообщение',
        self::CODE_TEMPLATE_NEW_USER => 'Новый пользователь (регистрация)',
        self::CODE_TEMPLATE_DISABLE_USER => 'Отключить (заблокировать) пользователя',
        self::CODE_TEMPLATE_ACTIVE_USER => 'Включить (разблокировать) пользователя',
        self::CODE_TEMPLATE_NEW_POST  => 'Новая статья',
        self::CODE_TEMPLATE_UPDATE_POST  => 'Статья была отредактирована',
    ];

    public $types;
    public $users;
    public $users_chkbox;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'msg_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'title', 'body', 'user_id', 'types'], 'required'],
            [['code', 'user_id', 'updated_at', 'users', 'created_at'], 'integer'],
            [['body'], 'string'],
            [['name', 'title'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['name', 'title', 'body'], 'filter', 'filter' => 'trim'],
            ['code', 'in', 'range'=>self::$codeTemplateList],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Код события',
            'name' => 'Название',
            'title' => 'Заголовок',
            'body' => 'Сообщение',
            'user_id' => 'От пользователя',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'users' => 'Кому',
            'types' => 'Тип уведомления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMsgTemplateUsers()
    {
        return $this->hasMany(MsgTemplateUser::className(), ['msg_template_id' => 'id']);
    }

    public function behaviors(){
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * Возвращает текст с описанием возможных параметров для использования в шаблоне
     */
    public static function returnDescriptionParam($code_id){
        $html = 'Имя сайта: {sitename}<BR>';
        switch ($code_id) {
            case self::CODE_TEMPLATE_NEW_USER:
                $html .= 'ID пользователя: {id}<BR>';
                $html .= 'Имя пользователя: {username}<BR>';
                $html .= 'Email пользователя: {email}<BR>';
                break;
            case self::CODE_TEMPLATE_DISABLE_USER:
                $html .= 'ID пользователя: {id}<BR>';
                $html .= 'Имя пользователя: {username}<BR>';
                $html .= 'Email пользователя: {email}<BR>';
                break;
            case self::CODE_TEMPLATE_ACTIVE_USER:
                $html .= 'ID пользователя: {id}<BR>';
                $html .= 'Имя пользователя: {username}<BR>';
                $html .= 'Email пользователя: {email}<BR>';
                break;
            case self::CODE_TEMPLATE_NEW_POST:
                $html .= 'ID поста: {id}<BR>';
                $html .= 'Email пользователя: {email}<BR>';
                $html .= 'Название статьи: {name}<BR>';
                $html .= 'Короткий текст статьи (первые 20 символов): {shortText}<BR>';
                $html .= 'Будет вставлена ссылка "читать далее" - переход к статье: {readMore}<BR>';
                break;
            case self::CODE_TEMPLATE_UPDATE_POST:
                $html .= 'ID поста: {id}<BR>';
                $html .= 'Email пользователя: {email}<BR>';
                $html .= 'Название статьи: {name}<BR>';
                $html .= 'Короткий текст статьи (первые 20 символов): {shortText}<BR>';
                $html .= 'Будет вставлена ссылка "читать далее" - переход к статье: {readMore}<BR>';
                break;
            default:
                $html .= '';
        }
        return $html;
    }

    public function getToUsers($return_all_users_if_set_all=false){
        $return_data = [];
        foreach (MsgTemplateUser::find()->where(['msg_template_id' => $this->id])->all() as $data){
            $return_data[$data->user->id] = $data->user->username;
        }
        if ($return_all_users_if_set_all && empty($return_data)){
            foreach (User::find()->where(['status' => User::STATUS_ACTIVE])->all() as $data){
                $return_data[$data->id] = $data->username;
            }
        }
        return $return_data;
    }

    public function getTypesVal(){
        $return_data = [];
        foreach (MsgTemplateType::find()->where(['msg_template_id' => $this->id])->all() as $data){
            $return_data[$data->type] = self::$typeNames[$data->type];
        }
        return $return_data;
    }

    public static function getUsers($id = null){
        $param = [];
        $param['status'] = User::STATUS_ACTIVE;
        if (!empty($id)){
            $where_param['id'] = $id;
        }

        $users = [];
        foreach(User::find()->where(['status'=>User::STATUS_ACTIVE])->all() as $data){
            $users[] = $data->id;
        }
        return $users;
    }

    public function beforeValidate() {
        $this->users = (((int)$this->users_chkbox == 1)?(null):($this->users));

        return parent::beforeValidate();
    }


    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
            MsgTemplateType::deleteAll(['msg_template_id' => $this->id]);
            MsgTemplateUser::deleteAll(['msg_template_id' => $this->id]);

            foreach ($this->types as $type){
                $model = new MsgTemplateType();
                $model->msg_template_id = $this->id;
                $model->type = $type;
                $model->save();
            }

            if (!empty($this->users)){
                $model = new MsgTemplateUser();
                $model->msg_template_id = $this->id;
                $model->user_id = $this->users;
                $model->save();
            }
    }

    //Подстраховка - данные в справочных таблицах и так удаляться должны через каскад
    public function afterDelete()
    {
        parent::afterDelete();
        MsgTemplateType::deleteAll(['msg_template_id' => $this->id]);
        MsgTemplateUser::deleteAll(['msg_template_id' => $this->id]);
    }

}
