<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Poll $model
 * @var kartik\widgets\ActiveForm $form
 */
?>
<div class="poll-form">
<?php
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
    echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Title...', 'maxlength'=>255]],

'question'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Question...','rows'=> 6]],

'select_min'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Select Min...']],

'select_max'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Select Max...']],


'start_time'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME, 'displayFormat' => 'd MMM, yyyy HH:mm','saveFormat' => 'php:Y-m-d H:i:s',]],

'end_time'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>[
    'type'=>DateControl::FORMAT_DATETIME,
    'displayFormat' => 'd MMM, yyyy HH:mm',
    'saveFormat' => 'php:Y-m-d H:i:s',

    // 'pluginOptions' => [
    //     'autoclose' => true,
    // ],
    ],
],

//'organizer_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Organizer ID...']],



    ]
    ]);



    echo $this->render('_options_form', ['model'=>$model, 'modelOptions'=> $modelOptions, 'form'=>$form]);


    ?>
    <?php
    /*
    echo Html::beginTag('div', ['class'=>'form-group']);
    echo Html::beginTag('div', ['class'=>'col-sm-offset-3 col-sm-9']);
    echo Html::a('<i class="glyphicon glyphicon-plus"></i> add Option', '#', ['class' => 'btn btn-primary pull-right', 'data-action' => 'add-option']);
    echo Html::a('<i class="glyphicon glyphicon-remove"></i> remove Option', '#', ['class' => 'btn btn-danger pull-right', 'data-action' => 'remove-option', 'style'=>'margin-right:5px;']);
    echo Html::endTag('div');
    echo Html::endTag('div');
    */
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
