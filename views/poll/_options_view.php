<?php
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

//$options = $model->getOptions()->with(['votes'])->all();
$options = $model->getOptions()->all();
$dataProvider = new ArrayDataProvider([
    'allModels' => $options,
]);
if (count($options) > 0) {
    echo Html::tag('h2', 'Options');
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'text',
                // 'format' => 'raw',
                // 'value' => function ($data) {
                //     return Html::a(Html::encode($data->text), ['option/view', 'id' => $data->id]);
                // }
            ],
            [
                'attribute' => 'votes',
                'format' => 'raw',
                'value' => function ($data) {
                    $votes = $data->votes;
                    $str = Html::beginTag('ul', ['class' => 'list-unstyled']);
                    foreach ($votes as $vote) {
                        $options = [];
                        $str .= Html::tag('li', Html::tag('span', $vote, $options));
                    }
                    $str .= Html::endTag('ul');
                    return $str;
                }
            ],
            'votesCount',
            [
                'attribute' => 'validVotes',
                'format' => 'raw',
                'value' => function ($data) {
                    $votes = $data->validVotes;
                    $str = Html::beginTag('ul', ['class' => 'list-unstyled']);
                    foreach ($votes as $vote) {
                        $options = [];
                        $str .= Html::tag('li', Html::tag('span', $vote, $options));
                    }
                    $str .= Html::endTag('ul');
                    return $str;
                }
            ],
            [
                'attribute' => 'validVotesCount',
                'header' => 'Votes to this option',
            ],
            [
                'header' => 'Votes to this option in percent',
                'format' => ['percent', '2'],
                'value' => function ($data) {
                    $used_count = $data->poll->getUsedCodesCount();
                    $votes_count = $data->getValidVotesCount();

                    if ($used_count > 0) {
                        return 100 / $used_count * $votes_count / 100;
                    };

                }
            ],
            [
                'attribute' => 'poll.usedCodesCount',
                'header' => 'Total Votes Received',
            ],
            [
                'attribute' => 'poll.unusedCodesCount',
                'header' => 'Total Votes not Submitted',
            ],
            [
                'attribute' => 'poll.validCodesCount',
                'header' => 'Total Codes sent',
            ],


            [
                'header' => 'Vote Acceptance',
                'format' => ['percent', '2'],
                'value' => function ($data) {
                    $total_count = $data->poll->getValidCodesCount();
                    $used_count = $data->poll->getUsedCodesCount();
                    if ($total_count > 0) {
                        return 100 / $total_count * $used_count / 100;
                    };
                }
            ],

        ],
    ]);
}
