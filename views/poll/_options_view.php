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
        ],
    ]);
}
