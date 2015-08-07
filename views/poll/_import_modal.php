<?php

use \Yii;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\models\forms\UploadForm;
use app\components\helpers\PollUrl;
use kartik\file\FileInput;

$model = new UploadForm();

Modal::begin([
    'id' => !empty($target)? $target : 'importModal',
    'header' => Html::tag('h4', Yii::t('app', 'Import From Excel'), ['class'=>'modal-title']),
]);
?>
<div class="member-excel-form">
    <?php
    echo Html::tag('p', Yii::t('app', 'Importing members will delete all existing members.'));
    ?>
    <?php $form = ActiveForm::begin(['action' => PollUrl::toRoute(['member/import', 'poll_id' => $poll->id]), 'options' => ['enctype' => 'multipart/form-data']]); ?>
    <?
    echo $form->field($model, 'excelFile')->widget(FileInput::classname(), [
        'pluginOptions' => [
            'showPreview' => false,
            'showRemove' => false,
            'showUpload' => false,
        ],
    ]);

    ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Import'), [
            'class' => 'btn btn-success',
            'data' => ['confirm' => Yii::t('app', 'This will delete all existing contacts. Are you sure you want to import?')],
        ]) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
Modal::end();
