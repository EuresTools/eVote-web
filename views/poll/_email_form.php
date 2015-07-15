<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use app\models\forms\EmailForm;

?>

<div class="email-form">
<?php
    $sendOptions = [
        EmailForm::EMAIL_TO_ALL => 'All Members',
        EmailForm::EMAIL_TO_UNUSED => 'Members who haven\'t voted',
        EmailForm::EMAIL_TO_USED => 'Members who have already voted',
        EmailForm::EMAIL_TO_INVALID => 'Members who have an invalid voting code'
    ];
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'sendMode' => ['type' => Form::INPUT_DROPDOWN_LIST, 'items' => $sendOptions],
            'subject' => ['type'=> Form::INPUT_TEXT, 'options' => ['maxlength'=>255]],
            'message' => ['type'=> Form::INPUT_TEXTAREA, 'options' => ['rows'=> 6]],
        ]
    ]);

?>
    <div class="form-group">
        <?php
        echo Html::submitButton('Send', ['class' => 'btn btn-primary']);
        ?>
    </div>
<?php
ActiveForm::end();
?>
</div>
