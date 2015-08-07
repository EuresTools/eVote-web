<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use app\models\Organizer;

/**
 * @var yii\web\View $this
 * @var app\models\User $model
 * @var kartik\widgets\ActiveForm $form
 */
?>
<div class="user-form">
<?php
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);

    echo $form->errorSummary([$model]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'username'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder' => Yii::t('app', 'Enter Username...'), 'maxlength'=>true]],
            'new_password'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder' => Yii::t('app', 'Enter Password to set one'), 'maxlength'=>true]],
            'is_admin'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder' => Yii::t('app', 'Enter Is Admin...')]],
            'organizer_id'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'items'=>ArrayHelper::map(Organizer::find()->all(), 'id', 'name'), 'options'=>['prompt' => Yii::t('app', 'None')]],

            //'created_at'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(), 'options'=>['type'=>DateControl::FORMAT_DATETIME]],
            //'updated_at'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(), 'options'=>['type'=>DateControl::FORMAT_DATETIME]],
            //'auth_key'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Auth Key...', 'maxlength'=>255]],
        ]
    ]);
    ?>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
        <?php
        echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
        ?>
        </div>
    </div>
<?php
ActiveForm::end();
?>
</div>
