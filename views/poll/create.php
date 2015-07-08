<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Poll $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Poll',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Polls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="poll-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
        'modelOptions' => $modelOptions,
    ]) ?>

</div>
