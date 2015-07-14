<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Code $model
 * @var kartik\widgets\ActiveForm $form
 */
?>
<div class="code-form">
<?php
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);
    echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'token'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Token...', 'maxlength'=>255]],

'poll_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Poll ID...']],

'member_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Member ID...']],

'code_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Is Valid...']],

'created_at'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

'updated_at'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

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
