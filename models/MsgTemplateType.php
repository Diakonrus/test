<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\models\MsgTemplate;

/**
 * This is the model class for table "msg_template_type".
 *
 * @property integer $id
 * @property integer $msg_template_id
 * @property integer $type
 * @property integer $updated_at
 * @property integer $created_at
 */
class MsgTemplateType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'msg_template_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['msg_template_id', 'type'], 'required'],
            [['msg_template_id', 'type', 'updated_at', 'created_at'], 'integer'],
            ['type', 'in', 'range'=>MsgTemplate::$typeList],
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
            'type' => 'Type',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function behaviors(){
        return [
            TimestampBehavior::className(),
        ];
    }

}
