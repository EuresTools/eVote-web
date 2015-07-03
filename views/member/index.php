<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Poll;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$poll_id = Yii::$app->request->get('poll_id');
$poll = Poll::find()->where(['id' => $poll_id])->one();
$this->title = "Members";
$this->params['breadcrumbs'][] = ['label' => 'Polls', 'url' => ['poll/index']];
$this->params['breadcrumbs'][] = ['label' => $poll->title, 'url' => ["poll/$poll_id"]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-index">

    <h1><?= Html::encode("$poll->title: $this->title") ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Member', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Import From Excel', ["poll/$poll_id/members/import"], ['class' => 'btn btn-info']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'group',
            //'poll_id',
            //'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
