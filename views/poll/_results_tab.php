<?php
use app\components\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Option;
use yii\widgets\DetailView;
use miloschuman\highcharts\Highcharts;
use yii\widgets\Pjax;

//$options = $model->getOptions()->with(['validVotes'])->all();
//$options = $model->getOptions()->withVoteCount()->all();
$options = $model->getOptions()->all();
global $optionIDs;
$optionIDs = ArrayHelper::getColumn($options, 'id');
$dataProvider = new ArrayDataProvider([
    'allModels' => $options,
    'sort' => [
        'attributes' => [
            'id',
            'text',
            'validVotesCount' => ['default' => SORT_DESC],
        ],
        // setting the id of the gridview so on pjax refresh the page doesn`t scroll to the top.
        'params'=> array_merge($_GET, ['#' => 'results_gridview']),
    ],
]);

Pjax::begin(['id' => 'options']);

// Status.
if ($model->isOver()) {
    echo Html::tag('h2', Yii::t('app', 'Status: Finished'), ['class' => 'status status-finished']);
} else if ($model->isOpen()) {
    echo Html::tag('h2', Yii::t('app', 'Status: Open'), ['class' => 'status status-open']);
} else {
    echo Html::tag('h2', Yii::t('app', 'Status: Not Started'), ['class' => 'status status-closed']);
}

// Column chart.
if ($model->hasStarted()) {
    $this->registerJsFile('@web/js/reflowChart.js', ['depends' => \yii\web\JqueryAsset::className(), 'depends' => miloschuman\highcharts\HighchartsAsset::className()]);
    echo Highcharts::widget([
        'htmlOptions' => ['id' => 'chartcontainer'],
        'options' => [
            'title' => ['text' => $model->question],
            'chart' => [
                //'type' => 'pie',
                'type' => 'column',
            ],
            'plotOptions' => [
                'column' => [
                    'colorByPoint' => true,
                ],
            ],
            'colors' => [
                '#0044CC',
                '#0088CC',
                '#51A351',
                '#F89406',
                '#BD362F',
            ],
            'credits' => [
                'enabled' => false,
            ],
            'xAxis' => [
                //'categories' => ArrayHelper::getColumn($options, 'text'),
                'categories' => ArrayHelper::getColumn($dataProvider->getModels(), 'text'),
            ],
            'yAxis' => [
                'title' => ['text' => 'Votes'],
                'allowDecimals' => false,
            ],
            'series' => [
                [
                    'name' => 'Votes',
                    'data' => ArrayHelper::getColumn($dataProvider->getModels(), function ($option) {
                        return intval($option->getValidVotesCount());
                    }),
                    'showInLegend' => false,
                ],
            ],
        ],
    ]);
}

?>

<?php
// Overview table.

$used = $model->getUsedCodesCount();
$unused = $model->getUnusedCodesCount();
$total = $model->getValidCodesCount();
echo Html::tag('h2', Yii::t('app', 'Overview'));
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'label' => Yii::t('app', 'Total Number of Voters'),
            'value' => $total,
        ],
        [
            'label' => Yii::t('app', 'Votes Submitted'),
            'value' => $used,
        ],
        [
            'label' => Yii::t('app', 'Votes Not Yet Submitted'),
            'value' => $unused,
        ],
        [
            'label' => Yii::t('app', 'Participation'),
            'format' => ['percent', '2'],
            'value' => $total > 0 ? ($used / $total) : 0,
        ],
    ],
]);

?>

<?php
// Votes.
echo Html::tag('h2', Yii::t('app', 'Votes'));
echo GridView::widget([
    'pjax' => false,
    'options' => ['id'=>'results_gridview'],
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute'=>'id',
            'label' => Yii::t('app', 'No.'),
            'headerOptions'=> ['class' => 'sort-numerical', 'style' => 'width: 30px; white-space: nowrap;'],
            'value' => function ($data, $key, $index, $widget) {
                global $optionIDs;
                return array_search($data->id, $optionIDs) + 1;
            }
        ],
        'text',
        [
            'attribute' => 'validVotesCount',
            'headerOptions'=> ['class' => 'sort-numerical'],
            'label' => Yii::t('app', 'Votes'),
        ],
    ],
]);
Pjax::end();

