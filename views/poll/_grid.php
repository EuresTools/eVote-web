<?php

use yii\helpers\Html;
use app\components\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Organizer;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\PollSearch $searchModel
 */

$columns = [
        ['class' => 'app\components\grid\ActionColumn'],
        //['class' => 'yii\grid\SerialColumn'],
        //'id',
        'title',
        'question:ntext',
        //'select_min',
        //'select_max',
        'membersCount',
        // 'start_time',
        // 'end_time',
        [
            'attribute' => 'organizer_id',
            'headerOptions'=> ['style' => 'width: 200px; white-space: nowrap;'],
            'header' => \Yii::t('app', 'Organizer'). ' (admin only)',
            'format' => 'raw',
            'visible' => \Yii::$app->user->identity->isAdmin(),
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>ArrayHelper::map(Organizer::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
            'filterWidgetOptions'=>[
                'pluginLoading' => false,
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => \Yii::t('app', 'Any Organizer')],
            'value'=>function ($data, $key, $index, $widget) {
                return $this->render('//system/columnviews/_organizer', ['data' => $data->organizer]);
            },
        ],
        // 'created_at',
        // 'updated_at',
        // 'created_by',
        // 'updated_by',
];

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $columns
]);
