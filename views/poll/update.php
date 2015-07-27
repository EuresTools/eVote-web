<?php

use yii\helpers\Html;
use app\models\Poll;

/**
 * @var yii\web\View $this
 * @var app\models\Poll $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => Poll::label(1),
]) . ' ' . $model->__toString();
$this->params['breadcrumbs'][] = ['label' => Poll::label(2), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->__toString(), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="poll-update">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
        'modelOptions' => $modelOptions,
    ]) ?>
    <?= $this->render('//system/leaving_prompt') ?>
</div>
