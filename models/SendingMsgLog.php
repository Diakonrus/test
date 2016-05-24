<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\models\MsgTemplate;

/**
 * This is the model class for table "sending_msg_log".
 *
 * @property integer $id
 * @property string $title
 * @property string $body
 * @property integer $user_id
 * @property integer $status
 * @property integer $type_msg
 * @property integer $updated_at
 * @property integer $created_at
 *
 * @property User $user
 */
class SendingMsgLog extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 1;
    const STATUS_READ = 2;

    public static $statusList = [
        self::STATUS_NEW,
        self::STATUS_READ
    ];
    public static $statusName = [
        self::STATUS_NEW => 'Новый',
        self::STATUS_READ => 'Просмотрен - отправлен',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sending_msg_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'body', 'user_id', 'status', 'type_msg'], 'required'],
            [['body'], 'string'],
            [['user_id', 'status', 'type_msg', 'updated_at', 'created_at'], 'integer'],
            ['status', 'in', 'range'=>self::$statusList],
            ['type_msg', 'in', 'range'=>MsgTemplate::$typeList],
            [['title'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'body' => 'Текст',
            'user_id' => 'Пользователь',
            'status' => 'Статус',
            'updated_at' => 'Updated At',
            'created_at' => 'Создан',
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

    public function sendEmail(){
        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($this->user->email)
            ->setSubject($this->title)
            ->setHtmlBody($this->body)
            ->send();

        $this->status = self::STATUS_READ;
        $this->update();
        return true;
    }


    /**
     * Заменяет в тексте параметры на значения
     */
    public static function replaceParam($text, $param = []){
        foreach ($param as $key=>$val){
            preg_match_all("/\{$key}/", $text, $matches);
            if (!empty($matches)){
                $text = str_replace($key, $val, $text);
            }
        }
        return $text;
    }

    public static function prepareParam($text, $model){
        //Предопределеные параметры, а также добавляем параметры в зависимости от модели
        $param = [];
        $param['{sitename}'] = Yii::$app->params['site_name'];
        foreach ($model->attributes as $key=>$val){
            $param["{{$key}}"] = $val;
        }

        switch (get_class($model)) {
            case 'app\models\Posts':
                $param['{shortText}'] = mb_substr($param['{body}'],0,20, "utf-8");
                $param['{readMore}'] = '<a href="http://'.$_SERVER['SERVER_NAME'].'/posts/view?id='.$model->id.'">читать далее</a>';
                $param['{email}'] = $model->user->username;
                break;
        }

        return self::replaceParam($text, $param);
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        if($this->status == self::STATUS_NEW){
            //В зависимости от типа уведомления - отдаем действие в нужный контроллер (можно переписать на класс и тд.)
            switch ($this->type_msg) {
                case MsgTemplate::TYPE_MSG_EMAIL:
                    $this->sendEmail();
                    break;
                case MsgTemplate::TYPE_MSG_BROWSEER:
                    //Для браузера действий не надо - данные бужем из лога выводить
                    break;
            }
        }
    }

    /**
     * @param $code
     * @param $modelContent
     * @return bool
     * @throws \Exception
     * @throws \yii\base\ErrorException
     */
    public static function addMsgLog($code, $modelContent){
        if (empty($code)) return true;

        if (!$models = MsgTemplate::find()->where(['code' => $code])->all()){
            Yii::$app->session->setFlash('alert', 'Для события `'.MsgTemplate::$codeTemplateNames[$code].'` шаблон не создан! Обратитесь к администратору с прозьбой создать шаблон для этого события. Уведомления не будут отправляться пока не будет создан шаблон для события `'.MsgTemplate::$codeTemplateNames[$code].'`!');
            //throw new \yii\base\ErrorException('Шаблон не найден!');
        }
        //В зависимости от типа уведомления - передаем данные в соответствующий контроллер
        foreach ($models as $data){
            foreach ($data->typesVal as $type=>$val){
                foreach($data->getToUsers(true) as $user_id => $user_name){
                    $model = new SendingMsgLog();
                    $model->title = self::prepareParam($data->title, $modelContent);
                    $model->body = self::prepareParam($data->body, $modelContent);
                    $model->user_id = $user_id;
                    $model->type_msg = $type;
                    $model->status = self::STATUS_NEW;
                    $model->save();
                }
            }
        }
    }
}
