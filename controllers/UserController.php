<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\RegistrationForm;
use app\models\User;
use yii\data\ActiveDataProvider;
use app\models\MsgTemplate;


class UserController extends Controller
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
                        'roles' => ['users_api_access'],
                    ],
                    [
                        'actions' => ['registration'],
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


    public function actionRegistration(){

        $model = new RegistrationForm();
        $model->scenario = $model::SCENARIO_REGISTRATION;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($user = $model->registration()){
                if ($user->status == User::STATUS_ACTIVE && Yii::$app->getUser()->login($user))
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Ошибка регистрации пользователя');
            Yii::error('Ошибка при регистрации');
            return $this->refresh();
        }


        return $this->render('registration', [
            'model' => $model,
        ]);
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new User();


        if ($model->load(Yii::$app->request->post())) {
            $model->setPasswordHash($model->password);
            $model->genAuthKey();
            if ($model->saveModel(MsgTemplate::CODE_TEMPLATE_NEW_USER)){
                return $this->redirect('index');
            }
            else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->setPasswordHash($model->password);
            $model->genAuthKey();
            if ($model->saveModel((($model->status == User::STATUS_NOT_ACTIVE)?(MsgTemplate::CODE_TEMPLATE_DISABLE_USER):(MsgTemplate::CODE_TEMPLATE_ACTIVE_USER)))){
                return $this->redirect('index');
            }
            else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
