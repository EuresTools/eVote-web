<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\MemberSearch;
use app\components\helpers\PollUrl;
use app\models\Code;
use app\models\Poll;

/**
 * @var yii\web\View $this
 * @var app\models\Poll $model
 */

$this->title = $model->__toString();
$this->params['breadcrumbs'][] = ['label' => Poll::label(2), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="poll-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <p>
        <?= Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('yii', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>


    <?php

    // Populate the attribute array for display.
    $attributes = ['question:ntext'];
    foreach ($model->getOptions()->all() as $index => $option) {
            $no = $index + 1;
            $attributes[] = ['attribute' => "Option $no", 'value' => $option->text];
    }

    ?>

    <?= Html::tag('h2', Poll::label(1)); ?>
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
    <?php

    ?>

    <?php

    echo $this->render('_members_view', [
        'model'=>$model,
        'memberDataProvider'=>$memberDataProvider,
        'memberSearchModel'=>$memberSearchModel
    ]);

    echo $this->render('_options_view', ['model'=>$model]);
    ?>
</div>
