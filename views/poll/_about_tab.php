<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\MemberSearch;
use app\components\helpers\PollUrl;
use app\models\Code;
use app\models\Poll;
use kartik\widgets\AlertBlock;
use kartik\widgets\Alert;

/**
 * @var yii\web\View $this
 * @var app\models\Poll $model
 */

?>


<?php

// Populate the attribute array for display.
$attributes = ['question:ntext', 'info:ntext'];
foreach ($model->getOptions()->all() as $index => $option) {
        $no = $index + 1;
        $attributes[] = ['attribute' => "Option $no", 'value' => $option->text];
}

?>

<?= Html::tag('h2', Poll::label(1)); ?>

<p>
<?
if ($model->isLocked()){
    echo AlertBlock::widget([
        'useSessionFlash' => false,
        'type' => AlertBlock::TYPE_ALERT,
        'delay' => false, // Don't automatically disappear.
        'alertSettings' => [
            'warning' => [
                'type' => Alert::TYPE_WARNING,
                'body' => Yii::t('app', 'This poll cannot be edited because it has already been accessed by a voter'),
                'closeButton'=> false,
            ],
        ],
    ]);
}
?>
    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary' . ($model->isLocked() ? ' disabled' : null)]) ?>
    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
            'method' => 'post',
        ],
    ]) ?>
    <?= Html::a(Yii::t('app', 'Preview'), ['//vote/preview', 'id' => $model->id], ['target'=>'_blank', 'class' => 'pull-right btn btn-primary']) ?>

</p>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => $attributes,
]) ?>

<?= Html::tag('h2', Yii::t('app', 'About')) ?>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        //'id',
        'title',
        //'question:ntext',
        [
            'attribute' => 'organizer',
            'format' => 'raw',
            'value' => Yii::$app->user->identity->isAdmin() ? Html::a(Html::encode($model->getOrganizer()->one()->name), ['organizer/view', 'id' => $model->getOrganizer()->one()->id]) : Html::encode($model->getOrganizer()->one()->name),
        ],
        'select_min',
        'select_max',
        'start_time:datetime',
        'end_time:datetime',
        'created_at:datetime',
        'updated_at:datetime',
        // 'created_by',
        // 'updated_by',
        'creator',
        'editor',
    ],
])?>

