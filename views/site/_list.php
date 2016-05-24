<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use app\models\SendingMsgLog;
?>

<div class="alert <?=(($model->status == SendingMsgLog::STATUS_NEW)?('alert-danger'):('alert-success'));?>">
    <h3><?= Html::encode($model->title) ?></h3>
    <?= HtmlPurifier::process($model->body) ?>
    <BR>
    <?php if ($model->status == SendingMsgLog::STATUS_NEW) { ?>
        <a style="margin-top:10px;" href="#" data-id="<?=$model->id;?>" class="notice_views btn btn-primary">Прочитано</a>
    <?php } ?>
</div>
