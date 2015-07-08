<?php

use yii\helpers\Html;
use app\components\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\CodeSearch $searchModel
 */

$columns = [
        ['class' => 'app\components\grid\ActionColumn'],
        //['class' => 'yii\grid\SerialColumn'],
        'id',
        'token',
        'poll_id',
        'member_id',
        'is_valid',
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
