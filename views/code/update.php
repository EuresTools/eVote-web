<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Code $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Code',
]) . ' ' . $model->__toString();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->__toString(), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="code-update">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <?= $this->render('//system/leaving_prompt') ?>
</div>
