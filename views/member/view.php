<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\helpers\PollUrl;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\Code;
use kartik\widgets\AlertBlock;
use kartik\widgets\Alert;

/**
 * @var yii\web\View $this
 * @var app\models\Member $model
 */

$this->title = $model->__toString();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Polls'), 'url' => ['poll/index']];
$this->params['breadcrumbs'][] = ['label' => $model->poll, 'url' => ['poll/view', 'id' => $model->poll_id]];
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => PollUrl::toRoute(['member/index', 'poll_id' => $model->poll_id])];
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
        <?= Html::button(Yii::t('app', 'Send Email'), ['class' => 'btn btn-warning pull-right', 'data' => ['toggle' => 'modal', 'target' => '#emailModal']]); ?>
    </p>

    <?= $this->render('_email_modal', ['model' => $model, 'target'=>'emailModal']); ?>

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
            'email',
            'name',
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
        'id' => 'member_contacts',
        'pjax' => true,
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $codes,
            'key' => 'id',
            'sort' => [
                'attributes' => [
                    'token',
                    'code_status',
                ],
            ],
        ]),
        'columns' => [
            [
                'attribute' => 'token',
                'contentOptions' => function($data) {
                    return $data->getHTMLOptions();
                },
                'value' => function ($data) { // use the __toString to print out either the cleartext token or scrabled.
                    return $data->__toString();
                }
            ],
            [
                'label' => Yii::t('app', 'Valid'),
                'attribute' => 'code_status',
                'format' => 'boolean',
                'value' => function ($data) {
                    return $data->isValid();
                }
            ],
            [
                'label' => Yii::t('app', 'Used'),
                'format' => 'boolean',
                'value' => function($data) {
                    return $data->isUsed();
                },
            ],
            'sent_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width:80px; text-align: center;'],
                'header'=>Yii::t('app', 'Actions'),
                'template' => '{invalidate}',
                'buttons' => [
                    'invalidate' => function ($url, $model) {
                        if ($model->isValid()) {
                            return Html::a('Invalidate', $url, [
                                //'title' => Yii::t('app', 'Invalidate'),
                                'class' => 'btn btn-danger',
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to Invalidate this Code?'),
                                'data-method' => 'post',
                                'data-pjax'=>'0',
                            ]);
                        } else {
                            //return Html::tag('span', '', ['class' => 'glyphicon glyphicon-ban-circle disabled']);
                        }
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    return $this->context->createUrl(['code/'.$action, 'id' => $key]);
                },
            ],
        ],
    ]);
    echo Html::a(Yii::t('app', 'Create New Code'), $this->context->createUrl(['code/create', 'member_id' => $model->id, 'poll_id' => $model->poll_id]), ['class' => 'btn btn-success pull-right', 'data-confirm'=>Yii::t('app', 'Creating a new code will invalidate any existing codes for this member. Are you sure you want to create a new code?')]);
    ?>
</div>

