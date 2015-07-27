<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\data\ArrayDataProvider;
use yii\bootstrap\Modal;
use app\models\forms\EmailForm;
use app\models\MemberSearch;
use app\components\helpers\PollUrl;

if ($memberDataProvider->getCount() >= 0) {
    echo Html::tag('h2', 'Members');
    //echo Html::beginTag('p');
    echo Html::a('Edit Members', ["poll/$model->id/members"], ['class' => 'btn btn-primary']);
    echo '&nbsp;';
    //echo Html::endTag('p');


    Modal::begin([
        'header' => Html::tag('h2', 'Send Email'),
        'toggleButton' => ['label' => 'Send Email', 'class' => 'btn btn-warning'],
    ]);

    $emailForm = new EmailForm();
    $emailForm->poll = $model;
    echo $this->render('_email_form', ['model' => $emailForm, 'poll' => $model]);

    Modal::end();

    Pjax::begin();
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
                    return Html::a(Html::encode($data->name), PollUrl::toRoute(['member/view', 'id' => $data->id, 'poll_id' => $data->poll_id]));
                }
            ],
            [
                'attribute' => 'code',
                'label' => 'Voting Code',
                'format' => 'raw',
                'value' => function ($data) {
                    $codes = $data->codes;
                    // Display the invalid tokens before the valid ones.
                    usort($codes, function ($a, $b) {
                        return $a->code_status > $b->code_status;
                    });
                    $str = Html::beginTag('ul', ['class' => 'list-unstyled']);
                    foreach ($codes as $code) {
                        $options = $code->getHTMLOptions();
                        $str .= Html::tag('li', Html::tag('span', $code, $options));
                    }
                    $str .= Html::endTag('ul');
                    return $str;
                }
            ],

        ],
   ]);
    Pjax::end();
}
