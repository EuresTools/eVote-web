<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\helpers\PollUrl;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\Code;

/**
 * @var yii\web\View $this
 * @var app\models\Member $model
 */

$this->title = $model->__toString();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Polls'), 'url' => ['poll/index']];
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
            'created_at:datetime',
            'updated_at:datetime',
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


    <?= Html::tag('h2', 'Contact Persons') ?>
    <?
    $contacts = $model->contacts;
    echo GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $contacts,
        ]),
        'columns' => [
            'name',
            'email'
        ],
    ]);
    ?>


    <?= Html::tag('h2', 'Voting Codes') ?>
    <?
    $codes = $model->codes;
    usort($codes, function($a, $b) {
        return $a->code_status > $b->code_status;
    });
    echo GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $codes,
        ]),
        'columns' => [
            [
                'attribute' => 'token',
                'contentOptions' => function($data) {
                    if ($data->code_status === Code::CODE_STATUS_INVALID) {
                        return ['class' => 'token-invalid', 'title' => 'This voting code has been invalidated'];
                    }
                    else if ($data->code_status === Code::CODE_STATUS_UNUSED) {
                        return ['class' => 'token-valid', 'title' => 'This code has not yet been used'];
                    }
                    else if ($data->code_status === Code::CODE_STATUS_USED) {
                        return ['class' => 'token-used', 'title' => 'A vote has been submitted using this code'];
                    }
                },
            ],
            [
                'label' => 'Valid',
                'attribute' => 'code_status',
                'format' => 'boolean',
                'value' => function ($data) {
                    return $data->isValid();
                }
            ],
            [
                'label' => 'Used',
                'format' => 'boolean',
                'value' => function($data) {
                    return $data->isUsed();
                },
            ],
            'sent_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width:80px; text-align: center;'],
                'header'=>'Actions',
                'template' => '{mybutton}',
                'buttons' => [
                    'mybutton' => function ($url, $model) {
                        if ($model->isValid()) {
                            return Html::a('<i class="glyphicon glyphicon-ban-circle"></i>', $url, [
                                        'title' => Yii::t('app', 'Invalidate'),
                            ]);
                        } else {
                            return Html::tag('span', '', ['class' => 'glyphicon glyphicon-ban-circle disabled']);
                        }
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    return $this->context->createUrl(['code/invalidate', 'id' => $model->id]);
                },
            ],
        ],
    ]);
    echo Html::a(Yii::t('app', 'New Code'), $this->context->createUrl(['code/create', 'member_id' => $model->id, 'poll_id' => $model->poll_id]), ['class' => 'btn btn-success pull-right']);

    ?>


</div>
