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
        'start_time:datetime',
        'end_time:datetime',
        // [
        //     'class' => '\kartik\grid\BooleanColumn',
        //     'attribute' => 'locked',
        //     'trueLabel' => Yii::t('app', 'Locked'),
        //     'falseLabel' => Yii::t('app', 'Open'),
        //     'headerOptions'=> ['style' => 'white-space: nowrap;'],
        //     'visible' => \Yii::$app->user->isAdmin(),
        // ],
        [
            'class'=>'kartik\grid\EditableColumn',
            'attribute'=>'locked',
            'format'=>'boolean',
            'visible' => \Yii::$app->user->isAdmin(),
            'refreshGrid' => true,
            //'header' => \Yii::t('app', 'locked'). ' (admin only)',
            'editableOptions'=>[
                'header' => \Yii::t('app', 'locked'). ' (admin only)',
                'size'=>'md',
                'data'=> array(0=>'Open', 1=>'Locked'),
                'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                'formOptions' => [
                    'action'=>Url::to(['ajax-update']),
                ],
                'displayValueConfig' => [ 0 => 'Open', 1 => 'Locked'],
                'containerOptions'=> [
                    'style' => 'text-align: center; display:block;',
                ],
                // 'displayValueConfig' => [ 0 => '<span class="glyphicon glyphicon-remove text-danger"> Open</span>', 1 => '<span class="glyphicon glyphicon-ok text-success"> Locked</span>'],
            ],
        ],
        [
            'attribute' => 'organizer_id',
            //'header' => \Yii::t('app', 'Organizer'). ' (admin only)',
            'width'=>'180px',
            'format' => 'raw',
            'visible' => \Yii::$app->user->isAdmin(),
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
        // 'created_at',
        // 'updated_at',
        // 'created_by',
        // 'updated_by',
];

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $columns,
    'id'=>'poll_grid',
]);
