<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>


<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Резюме Скляров П. Н.',
        'brandUrl' => 'https://drive.google.com/open?id=0B-usdaIF7S29X2gtQTdQbE5kZHM',
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Главная', 'url' => ['/']],
    ];

    if (\Yii::$app->user->can('template_access')){
        $menuItems[] = ['label' => 'Шаблоны сообщений', 'url' => ['/template/index']];
    }
    if (\Yii::$app->user->can('users_api_access')){
        $menuItems[] = ['label' => 'Пользователи', 'url' => ['/user/index']];
    }
    if (\Yii::$app->user->can('posts_access')){
        $menuItems[] = ['label' => 'Посты', 'url' => ['/posts/index']];
    }

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Вход', 'url' => ['/auth']];
        $menuItems[] = ['label' => 'Регистрация', 'url' => ['/registration']];
    } else {
        $menuItems[] = [
            'label' => 'Выход (' . Yii::$app->user->identity->username . ')',
            'url' => ['/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>




<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?=Yii::$app->params['site_name'];?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<!--- scripts -->
<?php $this->registerJsFile('/js/jquery-2.1.3.js', ['position' => \yii\web\View::POS_HEAD]); ?>
<?php $this->registerJsFile('/js/script.js'); ?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
