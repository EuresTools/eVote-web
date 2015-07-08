<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
* @var app\models\search\OrganizerSearch $searchModel
*/

$this->title = Yii::t('app', 'Organizers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organizer-index">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
<?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Organizer',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
</div>
