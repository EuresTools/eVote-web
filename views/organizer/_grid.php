<?php

use yii\helpers\Html;
use app\components\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\OrganizerSearch $searchModel
 */

$columns = [
        ['class' => 'app\components\grid\ActionColumn'],
        //['class' => 'yii\grid\SerialColumn'],
        'id',
        'name',
        'created_at',
        'updated_at',
        //'created_by',
        //'updated_by',
        'creator',
        'editor',
];


echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $columns
]);
