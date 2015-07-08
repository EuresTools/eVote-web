<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\MemberSearch;

/**
 * @var yii\web\View $this
 * @var app\models\Poll $model
 */

$this->title = $model->__toString();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Polls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="poll-view">
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


    <?php

    // Populate the attribute array for display.
    $attributes = ['question:ntext'];
    foreach ($model->getOptions()->all() as $index => $option) {
            $no = $index + 1;
            $attributes[] = ['attribute' => "Option $no", 'value' => $option->text];
    }

    ?>

    <h2>Poll</h2>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>


    <h2>About</h2>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'title',
            //'question:ntext',
            [
                'attribute' => 'organizer',
                'format' => 'raw',
                'value' => Yii::$app->user->identity->isAdmin() ? Html::a(Html::encode($model->getOrganizer()->one()->name), ['organizer/view', 'id' => $model->getOrganizer()->one()->id]) : Html::encode($model->getOrganizer()->one()->name),
            ],
            'select_min',
            'select_max',
            'start_time:datetime',
            'end_time:datetime',
            'created_at:datetime',
            'updated_at:datetime',
            // 'created_by',
            // 'updated_by',
            'creator',
            'editor',
        ],
    ])?>
    <?php
    $members = $model->getMembers()->all();
    if (count($members) >= 0) {
        echo Html::tag('h2', 'Members');
        echo Html::a('Edit Members', ["poll/$model->id/members"], ['class' => 'btn btn-primary']);
        echo GridView::widget([
            'dataProvider' => $memberDataProvider,
            'filterModel' => $memberSearchModel,
            //'itemView' => function($model, $key, $index, $widget) {
                //return $model->name;
            'columns' => [
                'name',
            ],
        ]);
    }
    ?>
    <?php
    //echo $this->render('_options_view', ['model'=>$model]);
    ?>
</div>
