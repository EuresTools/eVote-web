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
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes'=> $model->getFormFields(),
    ]);

    //print_pre($model->attributes(),'attributes');

    // test to submit option which is not an available option
    // <input type="checkbox" value="100000" name="VotingForm[options][]">
    ?>
    <div class="form-group">
        <?php
        echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Submit your vote') : Yii::t('app', 'Update your vote'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
        ?>
    </div>
<?php
ActiveForm::end();
?>
</div>

