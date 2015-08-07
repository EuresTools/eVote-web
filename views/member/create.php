<?php

use yii\helpers\Html;
use app\components\helpers\PollUrl;

/**
 * @var yii\web\View $this
 * @var app\models\Member $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Member',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Polls'), 'url' => ['poll/index']];
$this->params['breadcrumbs'][] = ['label' => $model->poll, 'url' => ['poll/view', 'id' => $model->poll_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
        'modelContacts' => $modelContacts,
    ]) ?>
    <?= $this->render('//system/leaving_prompt') ?>
</div>
