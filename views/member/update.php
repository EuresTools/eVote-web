<?php

use yii\helpers\Html;
use app\components\helpers\PollUrl;

/**
 * @var yii\web\View $this
 * @var app\models\Member $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Member',
]) . ' ' . $model->__toString();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Polls'), 'url' => ['poll/index']];
$this->params['breadcrumbs'][] = ['label' => $model->poll, 'url' => ['poll/view', 'id' => $model->poll_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => PollUrl::toRoute(['member/index', 'poll_id' => $model->poll_id])];
$this->params['breadcrumbs'][] = ['label' => $model->__toString(), 'url' => ['member/view', 'id' => $model->id, 'poll_id' => $model->poll_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

?>

<div class="member-update">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
