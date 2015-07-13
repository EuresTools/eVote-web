<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Poll;
use yii\helpers\Url;
use app\components\helpers\PollUrl;

/* @var $this yii\web\View */
/* @var $model app\models\Member */
$this->title = 'Import From Excel';
$this->params['breadcrumbs'][] = ['label' => 'Polls', 'url' => ['/poll/index']];
//$this->params['breadcrumbs'][] = ['label' => $this->context->getPollDisplay(), 'url' => ['/poll/view', 'id'=>$this->context->getPollId()]];
//$this->params['breadcrumbs'][] = ['label' => $this->context->getPollDisplay(), 'url' => PollUrl::toRoute(['/poll/view'])];
$this->params['breadcrumbs'][] = ['label' => $this->context->getPollDisplay(), 'url' => $this->context->createUrl(['/poll/view'])];



//$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => $this->context->createUrl(['index'])];
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
