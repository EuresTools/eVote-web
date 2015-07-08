<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Poll $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Poll',
]) . ' ' . $model->__toString();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Polls'), 'url' => ['index']];
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

</div>
