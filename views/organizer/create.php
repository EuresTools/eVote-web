<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Organizer $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Organizer',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Organizers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organizer-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
