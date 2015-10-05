<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use app\models\forms\EmailForm;
use app\components\helpers\PollUrl;

?>
<?= Html::tag('p', Html::encode(Yii::t('app', 'You can use the tags <member-name>, <member-group> and <voting-code> or <voting-link> (for clickable link) to customize your message for each member.'))); ?>
<div class="email-form">
<?php
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL, 'action' => PollUrl::toRoute(['email/sendsingle', 'member_id' => $member->id])]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'subject' => ['type'=> Form::INPUT_TEXT, 'options' => ['maxlength'=>255]],
            'message' => ['type'=> Form::INPUT_TEXTAREA, 'options' => ['rows'=> 6]],
        ]
    ]);

?>
    <div class="form-group">
        <?php
        echo Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary']);
        ?>
    </div>
<?php
ActiveForm::end();
?>
</div>
