<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Organizer $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Organizer',
]) . ' ' . $model->__toString();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Organizers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->__toString(), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="organizer-update">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
