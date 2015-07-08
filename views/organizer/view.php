<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\models\Organizer $model
 */

$this->title = $model->__toString();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Organizers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organizer-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'created_at',
            'updated_at',
            //'created_by',
            //'updated_by',
            'creator',
            'editor',
        ],
    ]) ?>
    <?= $this->render('_polls', [
        'model' => $model,
    ]) ?>
</div>
