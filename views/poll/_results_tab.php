<?php
use app\components\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Option;
use yii\widgets\DetailView;
use miloschuman\highcharts\Highcharts;

//$options = $model->getOptions()->with(['validVotes'])->all();
//$options = $model->getOptions()->withVoteCount()->all();
$options = $model->getOptions()->all();
$dataProvider = new ArrayDataProvider([
    'allModels' => $options,
    'sort' => [
        'attributes' => [
            'text',
            'validVotesCount',
        ],
    ],
]);

?>

<?php
// Status.

if ($model->isOver()) {
    echo Html::tag('h2', Yii::t('app', 'Status: Finished'), ['class' => 'status status-finished']);
} else if ($model->isOpen()) {
    echo Html::tag('h2', Yii::t('app', 'Status: Open'), ['class' => 'status status-open']);
} else {
    echo Html::tag('h2', Yii::t('app', 'Status: Not Started'), ['class' => 'status status-closed']);
}

?>


<?php
// Column chart.

if ($model->hasStarted()) {

    echo Highcharts::widget([
        'options' => [
            'title' => ['text' => $model->question],
            'chart' => [
                //'type' => 'pie',
                'type' => 'column',
            ],
            'plotOptions' => [
                'column' => [
                    'colorByPoint' => true,
                ],
            ],
            'colors' => [
                '#0044CC',
                '#0088CC',
                '#51A351',
                '#F89406',
                '#BD362F',
            ],
            'credits' => [
                'enabled' => false,
            ],
            'xAxis' => [
                'categories' => ArrayHelper::getColumn($options, 'text'),
            ],
            'yAxis' => [
                'title' => ['text' => 'Votes'],
                'allowDecimals' => false,
            ],
            'series' => [
                [
                    'name' => 'Votes',
                    'data' => ArrayHelper::getColumn($options, function ($option) {
                        return intval($option->getValidVotesCount());
                    }),
                    'showInLegend' => false,
                ],
            ],
        ],
    ]);
}

?>

<?php
// Overview table.

$used = $model->getUsedCodesCount();
$unused = $model->getUnusedCodesCount();
$total = $model->getValidCodesCount();
echo Html::tag('h2', Yii::t('app', 'Overview'));
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'label' => Yii::t('app', 'Votes Submitted'),
            'value' => $used,
        ],
        [
            'label' => Yii::t('app', 'Unused Voting Codes'),
            'value' => $unused,
        ],
        [
            'label' => Yii::t('app', 'Participation'),
            'format' => ['percent', '2'],
            'value' => $total > 0 ? ($used / $total) : 0,
        ],
    ],
]);

?>

<?php
// Standings.

echo Html::tag('h2', Yii::t('app', 'Standings'));
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'text',
        [
            'attribute' => 'validVotesCount',
            'headerOptions'=> ['class'=>'sort-numerical'],
            'label' => 'Votes',
        ],
    ],
]);

?>



<?php
// Pie chart.

//echo Highcharts::widget([
    //'options' => [
        //'title' => ['text' => $model->question],
        //'chart' => [
            //'type' => 'pie',
            ////'type' => 'column',
        //],
        ////'colors' => [
            ////'#0044CC',
            ////'#0088CC',
            ////'#51A351',
            ////'#F89406',
            ////'#BD362F',
        ////],
        //'credits' => [
            //'enabled' => false,
        //],
        //'series' => [
            //[
                //'name' => 'Votes',
                //'data' => ArrayHelper::getColumn($options, function ($option) {
                    //return intval($option->getValidVotesCount());
                //}),
                //'showInLegend' => false,
            //],
        //],
    //],
//]);

?>
















<?php
//if (count($options) > 0) {
    //echo Html::tag('h2', Option::label(2));
    //echo GridView::widget([
        //'dataProvider' => $dataProvider,
        //'columns' => [
            //[
                //'attribute' => 'text',
                //// 'format' => 'raw',
                //// 'value' => function ($data) {
                ////     return Html::a(Html::encode($data->text), ['option/view', 'id' => $data->id]);
                //// }
            //],
            //[
                //'attribute' => 'votes',
                //'format' => 'raw',
                //'value' => function ($data) {
                    //$votes = $data->votes;
                    //$str = Html::beginTag('ul', ['class' => 'list-unstyled']);
                    //foreach ($votes as $vote) {
                        //$options = [];
                        //$str .= Html::tag('li', Html::tag('span', $vote, $options));
                    //}
                    //$str .= Html::endTag('ul');
                    //return $str;
                //}
            //],
            //'votesCount',
            //[
                //'attribute' => 'validVotes',
                //'format' => 'raw',
                //'value' => function ($data) {
                    //$votes = $data->validVotes;
                    //$str = Html::beginTag('ul', ['class' => 'list-unstyled']);
                    //foreach ($votes as $vote) {
                        //$options = [];
                        //$str .= Html::tag('li', Html::tag('span', $vote, $options));
                    //}
                    //$str .= Html::endTag('ul');
                    //return $str;
                //}
            //],
            //[
                //'attribute' => 'validVotesCount',
                //'header' => Yii::t('app', 'Votes to this option'),
            //],
            //[
                //'header' => Yii::t('app', 'Votes to this option in percent'),
                //'format' => ['percent', '2'],
                //'value' => function ($data) {
                    //$used_count = $data->poll->getUsedCodesCount();
                    //$votes_count = $data->getValidVotesCount();

                    //if ($used_count > 0) {
                        //return 100 / $used_count * $votes_count / 100;
                    //};

                //}
            //],
            //[
                //'header' => Yii::t('app', 'Votes to this option percentage to totally sent'),
                //'format' => ['percent', '2'],
                //'value' => function ($data) {
                    //$total_count = $data->poll->getValidCodesCount();
                    //$votes_count = $data->getValidVotesCount();

                    //if ($total_count > 0) {
                        //return 100 / $total_count * $votes_count / 100;
                    //};

                //}
            //],
            //[
                //'attribute' => 'poll.usedCodesCount',
                //'header' => Yii::t('app', 'Total Votes Received'),
            //],
            //[
                //'attribute' => 'poll.unusedCodesCount',
                //'header' => Yii::t('app', 'Total Votes not Submitted'),
            //],
            //[
                //'attribute' => 'poll.validCodesCount',
                //'header' => Yii::t('app', 'Total Codes sent'),
            //],
            //[
                //'header' => Yii::t('app', 'Vote Acceptance'),
                //'format' => ['percent', '2'],
                //'value' => function ($data) {
                    //$total_count = $data->poll->getValidCodesCount();
                    //$used_count = $data->poll->getUsedCodesCount();
                    //if ($total_count > 0) {
                        //return 100 / $total_count * $used_count / 100;
                    //};
                //}
            //],
        //],
    //]);
//}
