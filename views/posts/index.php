<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Посты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новый пост', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'body:ntext',
            [
                'attribute'=>'user_id',
                'format' => 'raw',
                'value'=> function ($data) {
                    return $data->user->username;
                },
            ],
            [
                'attribute'=>'created_at',
                'format' => 'raw',
                'value'=> function ($data) {
                    return date('d.m.Y H:i:s',$data->created_at);
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'delete' => function ($model, $key, $index) {
                        return ((\Yii::$app->user->can('posts_access_action'))?(true):(false));
                    },
                    'update' => function ($model, $key, $index) {
                        return ((\Yii::$app->user->can('posts_access_action'))?(true):(false));
                    }
                ],
            ],

        ],
    ]); ?>
</div>
