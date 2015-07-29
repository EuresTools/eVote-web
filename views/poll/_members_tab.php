<?php
use yii\helpers\Html;
use app\components\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\bootstrap\Modal;
use app\models\Member;
use app\models\forms\EmailForm;
use app\models\MemberSearch;
use app\components\helpers\PollUrl;
use yii\widgets\ActiveForm;

if ($memberDataProvider->getCount() >= 0) {
    echo Html::tag('h2', Member::label(2));
    echo Html::beginTag('span');

    echo Html::a(Yii::t('app', 'Add Member'), [PollUrl::toRoute(['member/create', 'poll_id' => $model->id])], ['class' => 'btn btn-success']);
    echo '&nbsp;';
?>

<?php
// Import modal.
    echo $this->render('_import_modal', ['poll' => $model]);
?>

<?php
    echo Html::a(Yii::t('app', 'Delete All Members'), [PollUrl::toRoute(['member/clear', 'poll_id' => $model->id])], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => Yii::t('app', 'Are you sure you want to delete all members?'),
        ],
    ]);
?>

<?php
// Email modal.
    echo $this->render('_email_modal', ['model' => $model]);
?>


<?php
    echo Html::endTag('span');

    echo GridView::widget([
        'dataProvider' => $memberDataProvider,
        'filterModel' => $memberSearchModel,
        'columns' => [
            [
                'class' => 'app\components\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    //return Yii::$app->controller->createUrl([$action, 'id'=>$key]);
                    return PollUrl::toRoute(["member/$action", 'id' => $key, 'poll_id' => $model->poll_id]);
                }
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a(Html::encode($data->name), PollUrl::toRoute(['member/view', 'id' => $data->id, 'poll_id' => $data->poll_id]));
                }
            ],
            'group',
            'ContactsCount',
            [
                'attribute' => 'code',
                'label' => Yii::t('app', 'Voting Code'),
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
}
