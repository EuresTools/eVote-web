<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\models\forms\UploadForm;
use app\components\helpers\PollUrl;

$model = new UploadForm();

Modal::begin([
    'header' => Html::tag('h2', Yii::t('app', 'Import From Excel')),
    'toggleButton' => ['label' => Yii::t('app', 'Import From Excel'), 'class' => 'btn btn-primary'],
]);
?>
<div class="member-excel-form">

    <?php $form = ActiveForm::begin(['action' => PollUrl::toRoute(['member/import', 'poll_id' => $poll->id]), 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'excelFile')->fileInput() ?>
    <div class="form-group">
        <?= Html::submitButton('Import', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php Modal::end(); ?>
