<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\MsgTemplate;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\MsgTemplate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="msg-template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?=$form->field($model, 'user_id')->dropDownList(\yii\helpers\ArrayHelper::map(User::find(['status'=>User::STATUS_ACTIVE])->all(), 'id', 'username'));?>

    <?=$form->field($model, 'users')->dropDownList(\yii\helpers\ArrayHelper::map(User::find(['status'=>User::STATUS_ACTIVE])->all(), 'id', 'username'));?>

    <?= $form->field($model, 'users_chkbox')->checkbox(['value' => 1, 'label' => 'Выбрать всех']) ?>

    <?= $form->field($model, 'code')->dropDownList(MsgTemplate::$codeTemplateNames) ?>

    <div id="description_template"></div>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

    <?php
        //Выбраные, если не новая запись, типы
        $selected_param = [];
        if(!$model->isNewRecord){
            foreach ($model->typesVal as $key=>$data){
                $selected_param[$key] = ['selected ' => true];
            }
        }
    ?>
    <?=
    $form->field($model, 'types')
        ->dropDownList(
            MsgTemplate::$typeNames,
            [
                'multiple'=>'multiple',
                'options' =>$selected_param
            ]
        );

    ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php $this->registerJsFile('/js/msg_template.js'); ?>