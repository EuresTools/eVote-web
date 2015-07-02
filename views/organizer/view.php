<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\Organizer */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Organizers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organizer-view">

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


    <h2>Information</h2>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'name',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <?php 
        $polls = $model->getPolls()->all();
        $allModels = [];
        foreach ($polls as $poll) {
            //$attributes[] = ['label' => $poll->created_at, 'value' => $poll->question];
            $allModels[] = ['key' => "$poll->question"];
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $polls,//$allModels,
        ]);
    ?>

    <?php
        if (count($polls) > 0) {
            echo Html::tag('h2', 'Polls');
            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => function($model, $key, $index, $widget) {
                    return Html::a(Html::encode($model->question), ['poll/view', 'id' => $model->id], []);
                }
            ]);
        }
    ?>

</div>
