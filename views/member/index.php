<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
* @var app\models\search\MemberSearch $searchModel
*/

$this->title = Yii::t('app', 'Members');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-index">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', ['modelClass' => 'Member']), ['create', 'poll_id'=>$poll_id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Import From Excel', ["poll/$poll_id/members/import"], ['class' => 'btn btn-info']) ?>
    </p>
    <?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
</div>
