<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\helpers\PollUrl;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

/**
 * @var yii\web\View $this
 * @var app\models\Member $model
 */

$this->title = $model->__toString();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Polls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->poll, 'url' => ['poll/view', 'id' => $model->poll_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => PollUrl::toRoute(['member/index', 'poll_id' => $model->poll_id])];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="member-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <p>
        <?= Html::a(Yii::t('app', 'Update'), $this->context->createUrl(['update', 'id' => $model->id]), ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), $this->context->createUrl(['delete', 'id' => $model->id]), [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'group',
            'created_at',
            'updated_at',
            [
                'attribute' => 'created_by',
                'value' => $model->creator,
            ],
            [
                'attribute' => 'updated_by',
                'value' => $model->editor,
            ],
        ],
    ]) ?>

    <?= Html::tag('h2', 'Voting Codes') ?>
    <?
    $codes = $model->codes;
    usort($codes, function($a, $b) {
        return $a->is_valid > $b->is_valid;
    });
    echo GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $codes,
        ]),
        'columns' => [
            [
                'attribute' => 'token',
                'contentOptions' => function($data) {
                    if(!$data->is_valid) {
                        return ['class' => 'token-invalid', 'title' => 'This voting code has been invalidated'];
                    }
                    elseif(!$data->vote) {
                        return ['class' => 'token-valid', 'title' => 'This code has not yet been used'];
                    }
                    else {
                        return ['class' => 'token-used', 'title' => 'A vote has been submitted using this code'];
                    }
                },
            ],
            'is_valid:boolean',
            [
                'label' => 'Used',
                'format' => 'boolean',
                'value' => function($data) {
                    return $data->vote !== null;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width:260px;'],
                'header'=>'Actions',
                'template' => '{mybutton} {view} {delete}',
                'buttons' => [

                    //view button
                    'view' => function ($url, $model) {
                        return Html::a('<span class="fa fa-search"></span>View', $url, [
                                    'title' => Yii::t('app', 'View'),
                                    'class'=>'btn btn-primary btn-xs',
                        ]);
                    },
                    'mybutton' => function ($url, $model) {
                        return Html::a('<span class="fa fa-search"></span>View', $url, [
                                    'title' => Yii::t('app', 'My button'),
                                    'class'=>'btn btn-primary btn-xs',
                        ]);
                    },
                ],

                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        $url ='/jobs/view?id='.$model->jobid;
                        return $url;
                    }
                },

            ],
        ],
    ]);
    ?>


</div>
