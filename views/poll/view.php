<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\MemberSearch;
use app\components\helpers\PollUrl;
use app\models\Code;
use app\models\Poll;
use kartik\tabs\TabsX;

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
    <?php

    $items = [
        [
            'label' => '<i class="glyphicon glyphicon-info-sign"></i> ' . Yii::t('app', 'About'),
            'content' => $this->render('_about_tab', ['model' => $model]),
            'active' => !isset($tab) || $tab === 'about',
        ],
        [
            'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::t('app', 'Members'),
            'content' => $this->render('_members_tab', [
                'model' => $model,
                'memberDataProvider' => $memberDataProvider,
                'memberSearchModel' => $memberSearchModel,
            ]),
            'active' => isset($tab) && $tab === 'members',
        ],
        [
            'label' => '<i class="glyphicon glyphicon-signal"></i> ' . Yii::t('app', 'Results'),
            'content' => $this->render('_results_tab', ['model' => $model]),
            'active' => isset($tab) && $tab === 'results',
        ],
    ];

    echo TabsX::widget([
        'items' => $items,
        'position' => TabsX::POS_ABOVE,
        'encodeLabels' => false
    ]);

    ?>
</div>
