<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?=(($msg = Yii::$app->session->getFlash('alert'))?('<div class="alert alert-danger">'.$msg.'</div>'):(''));?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новый пользователь', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'email:email',
            [
                'attribute'=>'role',
                'format' => 'raw',
                'value'=> function ($data) {
                    return User::$userRoleName[$data->role];
                },
            ],
            [
                'attribute'=>'status',
                'format' => 'raw',
                'value'=> function ($data) {
                    return User::$statusesName[$data->status];
                },
            ],


            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>
</div>
