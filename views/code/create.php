<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Code $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Code',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="code-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
