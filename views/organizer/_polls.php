<?php
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

$polls = $model->getPolls()->all();
$dataProvider = new ArrayDataProvider([
    'allModels' => $polls,
]);
if (count($polls) > 0) {
    echo Html::tag('h2', 'Polls');
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a(Html::encode($data->title), ['poll/view', 'id' => $data->id]);
                }
            ],
        ],
    ]);
}
