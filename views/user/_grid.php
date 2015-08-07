<?php

use yii\helpers\Html;
use app\components\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Organizer;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\UserSearch $searchModel
 */

$columns = [
        ['class' => 'app\components\grid\ActionColumn'],
        //['class' => 'yii\grid\SerialColumn'],
        //'id',
        'username',
        // 'password_hash',
        // 'auth_key',
        // 'created_at',
        // 'updated_at',
        // 'created_by',
        // 'updated_by',
        [
            'class' => '\kartik\grid\BooleanColumn',
            'attribute' => 'is_admin',
            'trueLabel' => Yii::t('app', 'Yes'),
            'falseLabel' => Yii::t('app', 'No'),
        ],
        [
            'attribute' => 'organizer_id',
            'headerOptions'=> ['style' => 'width: 200px; white-space: nowrap;'],
            'header' => \Yii::t('app', 'Organizer'),
            'format' => 'raw',
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>ArrayHelper::map(Organizer::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
            'filterWidgetOptions'=>[
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => \Yii::t('app', 'Any Organizer')],
            'value'=>function ($data, $key, $index, $widget) {
                return $this->render('//system/columnviews/_organizer', ['data' => $data->organizer]);
            },
        ],
        'creator',
        'editor',
];

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $columns
]);
