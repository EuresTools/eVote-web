<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use app\models\forms\EmailForm;
use app\components\helpers\PollUrl;

?>
<?= Html::tag('p', Html::encode(Yii::t('app', 'You can use the tags <member-name>, <member-group> and <voting-code> to customize your message for each member.'))); ?>
<div class="email-form">
<?php
    $sendOptions = [
        EmailForm::EMAIL_TO_UNUSED => Yii::t('app', 'Members who haven\'t voted'),
        EmailForm::EMAIL_TO_USED => Yii::t('app', 'Members who have already voted'),
        EmailForm::EMAIL_TO_ALL => Yii::t('app', 'All Members'),
    ];
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL, 'action' => PollUrl::toRoute(['email/sendmultiple', 'poll_id' => $poll->id])]);
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
