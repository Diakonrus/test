<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "posts".
 *
 * @property integer $id
 * @property string $name
 * @property string $body
 * @property integer $user_id
 * @property integer $updated_at
 * @property integer $created_at
 *
 * @property User $user
 */
class Posts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'body', 'user_id'], 'required'],
            [['body'], 'string'],
            [['user_id', 'updated_at', 'created_at'], 'integer'],
            [['name'], 'string', 'min'=>10, 'max' => 255],
            [['body'], 'string', 'min'=>50],

            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['name', 'body'], 'filter', 'filter' => 'trim'],
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
            'body' => 'Пост',
            'user_id' => 'Автор',
            'updated_at' => 'Updated At',
            'created_at' => 'Дата создания',
        ];
    }

    public function behaviors(){
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function beforeValidate() {
        if($this->isNewRecord){
            $this->user_id = Yii::$app->user->id;
        }

        return parent::beforeValidate();
    }

    public function saveModel($code){
        if ($this->save()){
            $this->trigger(SendingMsgLog::addMsgLog($code, $this));
            return $this;
        }
        return false;
    }

}

