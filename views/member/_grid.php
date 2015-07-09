<?php

use yii\helpers\Html;
use app\components\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\MemberSearch $searchModel
 */

$columns = [


        [
            'class' => 'app\components\grid\ActionColumn',
            'urlCreator' => function ($action, $model, $key, $index) {
                return Yii::$app->controller->createUrl([$action, 'id'=>$key]);
            }
        ],

        //['class' => 'yii\grid\SerialColumn'],
        //'id',
        'name',
        'group',
        //'poll_id',
        //'created_at',
        // 'updated_at',
        // 'created_by',
        // 'updated_by',
];

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $columns
]);
