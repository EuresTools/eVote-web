<?php

use yii\helpers\Html;
use app\models\Poll;

/**
 * @var yii\web\View $this
 * @var app\models\Poll $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => Poll::label(1),
]);
$this->params['breadcrumbs'][] = ['label' => Poll::label(2), 'url' => ['index']];
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
