<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
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


    <h2>About</h2>
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
        $dataProvider = new ArrayDataProvider([
            'allModels' => $polls,
        ]);
        if(count($polls) > 0) {
            echo Html::tag('h2', 'Polls');
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'title',
                        'format' => 'raw',
                        'value' => function($data) {
                            return Html::a(Html::encode($data->title), ['poll/view', 'id' => $data->id]);
                        }
                    ],
                ],
            ]);
        }
    ?>

</div>
