<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Poll;

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
* @var app\models\search\PollSearch $searchModel
*/

$this->title = Yii::t('app', Poll::label(2));
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="poll-index">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
<?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => Poll::label(1),
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
</div>
