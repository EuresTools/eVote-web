<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Member $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Member',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
