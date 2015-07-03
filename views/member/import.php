<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Poll;


/* @var $this yii\web\View */
/* @var $model app\models\Member */

$poll_id = Yii::$app->request->get('poll_id');
$poll = Poll::find()->where(['id' => $poll_id])->one();
$this->title = 'Import From Excel';
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $poll->title, 'url' => ["poll/$poll_id"]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-create">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <div class="member-excel-form">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'excelFile')->fileInput() ?>
        <div class="form-group">
            <?= Html::submitButton('Import', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
