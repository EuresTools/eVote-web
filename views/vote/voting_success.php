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
$this->title = 'Vote success';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Token Input'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'voting');
?>
<div class="voting-success">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="jumbotron">
        <h1>Vote successfully submitted!</h1>
        <p>You have successfully submitted your vote. Thank you for your collaboration.<br /> We will get an information about the voting result after the voting period is over.</p>
        <p><a class="btn btn-primary btn-lg" href="<?=Url::home()?>" role="button">Back to Home</a></p>
    </div>
</div>
