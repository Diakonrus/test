<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\MsgTemplate;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Шаблоны сообщений';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="msg-template-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новая запсь', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'code',
                'format' => 'raw',
                'value'=> function ($data) {
                    return MsgTemplate::$codeTemplateNames[$data->code];
                },
            ],

            'name',

            [
                'attribute'=>'user_id',
                'format' => 'raw',
                'value'=> function ($data) {
                    return $data->user->username;
                },
            ],

            [
                'attribute'=>'users',
                'format' => 'raw',
                'value'=> function ($data) {
                    return (empty($user = $data->getToUsers())) ? 'Все' : implode("; ", $user);
                },
            ],

            'title',
            'body:ntext',

            [
                'attribute'=>'types',
                'format' => 'raw',
                'value'=> function ($data) {
                    return implode("<BR>", $data->typesVal);
                },
            ],


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
