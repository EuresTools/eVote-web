<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Member $model
 * @var kartik\widgets\ActiveForm $form
 */
?>
<div class="member-form">
<?php 
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);
    echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 2,
    'attributes' => [

'name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder' => Yii::t('app', 'Enter Name...'), 'maxlength'=>255]],

'group'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder' => Yii::t('app', 'Enter Group...'), 'maxlength'=>255]],

    ]
    ]);

    echo $this->render('_contact_form', ['model' => $model, 'modelContacts'=> $modelContacts, 'form' => $form]);
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
