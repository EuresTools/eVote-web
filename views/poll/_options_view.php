<?php
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

$options = $model->getOptions()->all();
$dataProvider = new ArrayDataProvider([
    'allModels' => $options,
]);
if (count($options) > 0) {
    echo Html::tag('h2', 'Options');
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'text',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a(Html::encode($data->text), ['option/view', 'id' => $data->id]);
                }
            ],
        ],
    ]);
}
