<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Poll */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="poll-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'question')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'select_min')->textInput() ?>

    <?= $form->field($model, 'select_max')->textInput() ?>

    <?= $form->field($model, 'start_time')->widget(DateTimePicker::className(), [
        'convertFormat' => true,
        'pluginOptions' => [
            'startDate' => (new DateTime('NOW'))->format('Y-m-d H:i'),
            'todayHighlight' => true,
            'autoclose' => true,
        ],
    ]) ?>
    
    <?= $form->field($model, 'end_time')->widget(DateTimePicker::className(), [
        'convertFormat' => true,
        'pluginOptions' => [
            'startDate' => (new DateTime('NOW'))->format('Y-m-d H:i'),
            'todayHighlight' => true,
            'autoclose' => true,
        ],
    ]) ?>

    <?php //echo $form->field($model, 'organizer_id')->textInput() ?>

    <?php //echo $form->field($model, 'created_at')->textInput() ?>

    <?php //echo $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
