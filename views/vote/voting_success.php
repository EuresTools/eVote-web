<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var app\models\Poll $model
 */
/*
$this->title = Yii::t('app', 'voting for {pollQuestion}: ', [
    'pollQuestion' => 'Poll Question',
]) . ' ' . $model->__toString();
*/
$this->title = Yii::t('app', 'Vote success');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Token Input'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'voting');
?>
<div class="voting-success">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="jumbotron">
        <h1><?=Yii::t('app', 'Vote successfully submitted!')?></h1>
        <p><?=Yii::t('app', 'You have successfully submitted your vote. Thank you.')?></p>
        <p><a class="btn btn-primary btn-lg" href="<?=Url::home()?>" role="button"><?=Yii::t('app', 'Back to Home')?></a></p>
    </div>
</div>
