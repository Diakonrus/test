<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Posts */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Посты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-view">

    <?=(($msg = Yii::$app->session->getFlash('alert'))?('<div class="alert alert-danger">'.$msg.'</div>'):(''));?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if(\Yii::$app->user->can('posts_access_action')){ ?>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены что хотите удалить эту запись?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php } ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'body:ntext',
            [
                'label' => 'Автор',
                'value' => $model->user->username
            ],
            [
                'label' => 'Дата создания',
                'value' => date('d.m.Y H:i:s', $model->created_at)
            ],
        ],
    ]) ?>

</div>
