<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
/* @var $this yii\web\View */

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->params['site_name'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sending-msg-log-index">

    <h1>Уведомления</h1>

    <?=(($msg = Yii::$app->session->getFlash('alert'))?('<div class="alert alert-danger">'.$msg.'</div>'):(''));?>

    <div id="block_list_view_notice">
    <?=
    ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_list',
    ]);
    ?>
    </div>

</div>
<?php $this->registerJsFile('/js/notice_lists.js'); ?>