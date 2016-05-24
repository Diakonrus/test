<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "msg_template_user".
 *
 * @property integer $id
 * @property integer $msg_template_id
 * @property integer $user_id
 * @property integer $updated_at
 * @property integer $created_at
 *
 * @property User $user
 * @property MsgTemplate $msgTemplate
 */
class MsgTemplateUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'msg_template_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['msg_template_id', 'user_id'], 'required'],
            [['msg_template_id', 'user_id', 'updated_at', 'created_at'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['msg_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => MsgTemplate::className(), 'targetAttribute' => ['msg_template_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'msg_template_id' => 'Msg Template ID',
            'user_id' => 'User ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMsgTemplate()
    {
        return $this->hasOne(MsgTemplate::className(), ['id' => 'msg_template_id']);
    }
}
