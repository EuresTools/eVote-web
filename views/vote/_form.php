<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var kartik\widgets\ActiveForm $form
 */

?>
<div class="voting-form">
<?php
    echo Html::tag('h1', $model->header);
    echo Html::tag('p', $model->question, ['class'=>'well']);
    echo Html::tag('p', $model->getOptionsCountText(), ['class'=>'options-count']);  // text like Note: Please select minimum {min} maximum {maximum} of options.
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes'=> $model->getFormFields(),
    ]);

    echo $form->field($model, 'vote_submitted')->hiddenInput()->label(false);
    ?>
    <div class="form-group">
        <?php

        if ($preview) {
            echo Html::a($model->isNewRecord ? Yii::t('app', 'Submit your vote') : Yii::t('app', 'Update your vote'), '#', ['onclick'=>'alert("This will submit the vote.\n\nDisabled for this preview!"); return false;', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
        } else {
            echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Submit your vote') : Yii::t('app', 'Update your vote'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
        }

        ?>
    </div>
<?php
ActiveForm::end();
?>
</div>
<script type="text/javascript">
<?php $this->beginBlock('JS_READY') ?>
    $('#votingform-options input[type="checkbox"]').on(
        "change", // Bind handlers for multiple events change und click brauch ich doch nicht.
        function(event) {
            var checkboxes_checked = $('#votingform-options input[type="checkbox"]:checked').length;
            $('.options-count .options-counter').html(checkboxes_checked);
        }
    );
<?php $this->endBlock(); ?>
</script>
<?php
yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_READY'], yii\web\View::POS_READY);
