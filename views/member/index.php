<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\AlertBlock;
use kartik\widgets\Alert;

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
* @var app\models\search\MemberSearch $searchModel
*/

$this->title = Yii::t('app', 'Members');
$this->params['breadcrumbs'][] = ['label' => 'Polls', 'url' => ['/poll/index']];
$this->params['breadcrumbs'][] = ['label' => $this->context->getPollDisplay(), 'url' => ['/poll/view', 'id'=>$this->context->getPollId()]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
foreach(Yii::$app->getSession()->getAllFlashes() as $key => $arr) {
    foreach($arr as $message) {
        echo AlertBlock::widget([
            'useSessionFlash' => false,
            'type' => AlertBlock::TYPE_ALERT,
            'delay' => false, // Don't automatically disappear.
            'alertSettings' => [
                'warning' => [
                    'type' => Alert::TYPE_DANGER,
                    'body' => $message,
                ],
            ],
        ]);
    }
}
?>

<div class="member-index">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', ['modelClass' => 'Member']), ['create', 'poll_id'=>$this->context->getPollId()], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Import From Excel', ['import', 'poll_id'=>$this->context->getPollId()], ['class' => 'btn btn-info']) ?>
    </p>
    <?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
</div>
