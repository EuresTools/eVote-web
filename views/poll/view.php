<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\MemberSearch;
use app\components\helpers\PollUrl;
use app\models\Code;


/**
 * @var yii\web\View $this
 * @var app\models\Poll $model
 */

$this->title = $model->__toString();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Polls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="poll-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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

    <?= Html::tag('h2', 'Poll'); ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>


    <?= Html::tag('h2', 'About') ?>
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
    if ($memberDataProvider->getCount() >= 0) {
        echo Html::tag('h2', 'Members');
        echo Html::a('Edit Members', ["poll/$model->id/members"], ['class' => 'btn btn-primary']);
        echo Html::a('Send Email', ["poll/$model->id/members/email"], ['class' => 'btn btn-warning']);
        echo GridView::widget([
            'dataProvider' => $memberDataProvider,
            'filterModel' => $memberSearchModel,
            //'itemView' => function($model, $key, $index, $widget) {
                //return $model->name;
            'columns' => [
                [
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return Html::a(Html::encode($data->name), PollUrl::toRoute(['member/view', 'id' => $data->id, 'poll_id' => $data->poll_id]));;
                    }
                ],
                [
                    'attribute' => 'code',
                    'label' => 'Voting Code',
                    'format' => 'raw',
                    'value' => function($data) {
                        $codes = $data->codes;
                        // Display the invalid tokens before the valid ones.
                        usort($codes, function($a, $b) {
                            return $a->code_status > $b->code_status;
                        });
                        $str = Html::beginTag('ul', ['class' => 'list-unstyled']);
                        foreach($codes as $code) {
                            if($code->isValid() && $code->isUsed()) {
                                $str .= Html::tag('li', Html::tag('span', $code, ['class' => 'token-used', 'title' => 'A vote has been submitted using this code']));
                            }
                            elseif($code->isValid()) {
                                $str .= Html::tag('li', Html::tag('span', $code, ['class' => 'token-valid', 'title' => 'This code has not yet been used']));
                            } else {
                                $str .= Html::tag('li', Html::tag('span', $code, ['class' => 'token-invalid', 'title' => 'This voting code has been invalidated']));
                            }
                        }
                        $str .= Html::endTag('ul');
                        return $str;

                    }
                ],

            ],
        ]);
    }
    ?>
    <?php
    //echo $this->render('_options_view', ['model'=>$model]);
    ?>
</div>
