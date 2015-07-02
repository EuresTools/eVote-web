<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Poll */

$this->title = 'Update Poll: ' . ' ' . $poll->id;
$this->params['breadcrumbs'][] = ['label' => 'Polls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $poll->id, 'url' => ['view', 'id' => $poll->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="poll-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'poll' => $poll,
        'options' => $options,
    ]) ?>

</div>
