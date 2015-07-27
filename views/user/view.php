<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'username',
            //'password_hash',
            //'auth_key',
            'is_admin:boolean',
            //'organizer',
            [
                'attribute' => 'organizer_id',
                'format' => 'raw',
                'value' => $model->isOrganizer() ? Html::a(Html::encode($model->organizer), ['/organizer/view', 'id' => $model->organizer->getPrimaryKey()]) : 'None'
                //'value' => $model->isOrganizer() ? Html::a(Html::encode($model->getOrganizer()->one()->name), ['/organizer/view', 'id' => $model->getOrganizer()->one()->id]) : 'None'
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
