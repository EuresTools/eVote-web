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
        'is_admin:boolean',
        // 'organizer_id',
        // 'created_at',
        // 'updated_at',
        // 'created_by',
        // 'updated_by',
        // [
        //         'attribute' => 'organizer_id',
        //         'format' => 'raw',
        //         'value' => $model->isOrganizer() ? Html::a(Html::encode($model->organizer), ['/organizer/view', 'id' => $model->organizer->getPrimaryKey()]) : 'None'
        //         //'value' => $model->isOrganizer() ? Html::a(Html::encode($model->getOrganizer()->one()->name), ['/organizer/view', 'id' => $model->getOrganizer()->one()->id]) : 'None'
        // ],
        //'organizer',
        //
        [
            'attribute'=>'organizer_id',
            'vAlign'=>'middle',
            'width'=>'180px',
            'value'=>function ($model, $key, $index, $widget) {
                if ($model->organizer) {
                    return Html::a(
                        $model->organizer,
                        ['/organizer/view', 'id' => $model->organizer->getPrimaryKey()],
                        ['title'=>'View organizer detail']
                    );
                }
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>ArrayHelper::map(Organizer::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions' => ['placeholder' => 'Any Organizer'],

            /*
            'filterType'=>GridView::FILTER_TYPEAHEAD,
            'filterWidgetOptions'=>[
                'pluginOptions' => ['allowClear' => true],
                'dataset'=>[['local'=>ArrayHelper::map(Organizer::find()->orderBy('name')->asArray()->all(), 'id', 'name')]],
            ],
            'filterInputOptions' => ['placeholder' => 'Any Organizer'],
            */
            'format'=>'raw'
        ],

        'creator',
        'editor',
];

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $columns
]);
