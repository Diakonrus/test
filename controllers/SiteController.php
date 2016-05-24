<?php

namespace app\controllers;

use app\models\MsgTemplate;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SendingMsgLog;
use yii\data\ActiveDataProvider;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                    [
                        'actions' => ['index', 'login', 'logout', 'error'],
                        'allow' => true,
                        'roles' => ['guest'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) { $this->redirect('auth'); }

        $dataProvider = new ActiveDataProvider([
            'query' => SendingMsgLog::find()->where(['user_id' => \Yii::$app->user->id, 'type_msg'=>MsgTemplate::TYPE_MSG_BROWSEER]),
            'sort'=> ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLogin(){
        if (!Yii::$app->user->isGuest){ return $this->goHome(); }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionAjax(){
        if (Yii::$app->request->post('SendingMsgLog')){
            if ($model = SendingMsgLog::find()
                ->where(['user_id' => \Yii::$app->user->id, 'id'=>Yii::$app->request->post('SendingMsgLog')['id'] ])
                ->one()
            ){
                $model->status = SendingMsgLog::STATUS_READ;
                $model->save();
                return \yii\helpers\Json::encode('ok');
            }
        }
        Yii::$app->end();
    }

}
