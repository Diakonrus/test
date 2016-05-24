<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsgTemplate */

$this->title = 'Update Msg Template: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны сообщений', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="msg-template-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
