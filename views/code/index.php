<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
* @var app\models\search\CodeSearch $searchModel
*/

$this->title = Yii::t('app', 'Codes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="code-index">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
<?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Code',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
</div>
