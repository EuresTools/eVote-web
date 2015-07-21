<?php
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

$options = $model->getOptions()->with(['votes'])->all();
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
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a(Html::encode($data->text), ['option/view', 'id' => $data->id]);
                }
            ],
            /*
            [
                'attribute' => 'votes',
                'format' => 'raw',
                'value' => function ($data) {
                    $votes = $data->votes;
                    // Display the invalid tokens before the valid ones.
                    // usort($codes, function ($a, $b) {
                    //     return $a->code_status > $b->code_status;
                    // });
                    $str = Html::beginTag('ul', ['class' => 'list-unstyled']);
                    foreach ($votes as $vote) {
                        //$options = $vote->getHTMLOptions();
                        $options = [];
                        $str .= Html::tag('li', Html::tag('span', $vote, $options));
                    }
                    $str .= Html::endTag('ul');
                    return $str;
                }
            ],
            */
            'votesCount',
        ],
    ]);
}
