<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\MsgTemplate;

/* @var $this yii\web\View */
/* @var $model app\models\MsgTemplate */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны сообщений', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Просмотр';
?>
<div class="msg-template-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'Код события',
                'value' => MsgTemplate::$codeTemplateNames[$model->code]
            ],
            [
                'label' => 'От пользователя',
                'value' => $model->user->username
            ],
            [
                'label' => 'Кому',
                'value' => (empty($user = $model->getToUsers())) ? 'Все' : implode("; ", $user)
            ],
            'name',
            'title',
            'body:ntext',
            [
                'label' => 'Тип уведомления',
                'value' => implode("; ", $model->getTypesVal())
            ],
        ],
    ]) ?>

</div>
