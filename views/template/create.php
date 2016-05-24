<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsgTemplate */

$this->title = 'Create Msg Template';
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны сообщений', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Новая запись';
?>
<div class="msg-template-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
