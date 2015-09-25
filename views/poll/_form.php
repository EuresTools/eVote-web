<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use app\models\Organizer;

/**
 * @var yii\web\View $this
 * @var app\models\Poll $model
 * @var kartik\widgets\ActiveForm $form
 */
?>
<div class="poll-form">
<?php
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder' => Yii::t('app', 'Enter Title...'), 'maxlength'=>255]],
            'question'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder' => Yii::t('app', 'Enter Question...'), 'rows'=> 6]],
            'info' => ['type' => Form::INPUT_TEXTAREA, 'options' => ['placeholder' => Yii::t('app', 'Enter Additional Information...'), 'rows' => 3]],
        ]
    ]);

if (\Yii::$app->user->isAdmin()) {
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [
            'organizer_id'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'items'=>ArrayHelper::map(Organizer::find()->all(), 'id', 'name'), 'options'=>['prompt' => Yii::t('app', 'None')]],
        ],
    ]);
}

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => [
            'select_min'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter Select Min...')]],
            'select_max'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter Select Max...')]],
            'start_time' => [
                'type'=> Form::INPUT_WIDGET,
                'widgetClass'=>DateControl::classname(),
                'options' => [
                    'type'=>DateControl::FORMAT_DATETIME,
                    //'displayFormat' => 'd MMM, yyyy HH:mm',
                    // 'saveFormat' => 'php:Y-m-d H:i:s',
                    // datepicker options
                    'options'=> [
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'initialDate'=> date('Y-m-d H:i:00'),
                        ],
                    ]
                ],
            ],
            'end_time' => [
                'type'=> Form::INPUT_WIDGET,
                'widgetClass' => DateControl::classname(),
                'options' => [
                    'type'=>DateControl::FORMAT_DATETIME,
                    // 'displayFormat' => 'd MMM, yyyy HH:mm',
                    // 'saveFormat' => 'php:Y-m-d H:i:s',
                    // datepicker options
                    'options'=> [
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'initialDate'=> date('Y-m-d H:i:00'),
                        ],
                    ],
                ],
            ],
        ],
    ]);

    echo $this->render('_options_form', ['model'=>$model, 'modelOptions'=> $modelOptions, 'form'=>$form]);

    ?>
    <div class="form-group">
        <?php
        echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
        ?>
    </div>
<?php
ActiveForm::end();
?>
</div>
