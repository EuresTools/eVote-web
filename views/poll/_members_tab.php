<?php
use yii\helpers\Html;
use app\components\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\bootstrap\Modal;
use app\models\Member;
use app\models\Code;
use app\models\forms\EmailForm;
use app\models\MemberSearch;
use app\components\helpers\PollUrl;
use yii\widgets\ActiveForm;
use kartik\widgets\AlertBlock;
use kartik\widgets\Alert;

// display all "import" flash messages
foreach (Yii::$app->getSession()->getAllFlashes() as $key => $arr) {
    if ($key === 'import') {
        foreach ($arr as $message) {
            echo AlertBlock::widget([
                'useSessionFlash' => false,
                'type' => AlertBlock::TYPE_ALERT,
                'delay' => false, // Don't automatically disappear.
                'alertSettings' => [
                    'warning' => [
                        'type' => Alert::TYPE_DANGER,
                        'body' => $message,
                    ],
                ],
            ]);
        }
    }
}

echo Html::tag('h2', Member::label(2));
echo Html::beginTag('p');

    echo Html::a(Yii::t('app', 'Add Member'), [PollUrl::toRoute(['member/create', 'poll_id' => $model->id])], ['class' => 'btn btn-success']);
    echo '&nbsp;';
    echo Html::button(Yii::t('app', 'Import From Excel'), ['class' => 'btn btn-primary', 'data' => ['toggle' => 'modal', 'target' => '#importModal']]);
    echo '&nbsp;';

    echo Html::a(Yii::t('app', 'Delete All Members'), [PollUrl::toRoute(['member/clear', 'poll_id' => $model->id])], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => Yii::t('app', 'Are you sure you want to delete all members?'),
        ],
    ]);

if (\Yii::$app->user->isAdmin()) {
    echo '&nbsp;';
    echo Html::button(Yii::t('app', 'get Contact Emails'), ['class' => 'btn btn-success', 'data' => ['toggle' => 'modal', 'target' => '#contactEmailsModal']]);
}


    echo '&nbsp;';
    echo Html::button(Yii::t('app', 'Send Email'), ['class' => 'btn btn-warning pull-right', 'data' => ['toggle' => 'modal', 'target' => '#emailModal']]);

echo Html::endTag('p');

// render Import modal window
echo $this->render('_import_modal', ['poll' => $model, 'target'=>'importModal']);
// render Email modal window
echo $this->render('_email_modal', ['model' => $model, 'target'=>'emailModal']);

if (\Yii::$app->user->isAdmin()) {
    echo $this->render('_contact_emails_modal', ['model' => $model, 'target'=>'contactEmailsModal']);
}


$demoCodes = Html::beginTag('ul', ['class' => 'list-unstyled']);
$demoCodes .= Html::tag('li', Html::tag('span', 'Invalid Code', Code::getInvalidHTMLOptions()));
$demoCodes .= Html::tag('li', Html::tag('span', 'Unused Code', Code::getUnusedHTMLOptions()));
$demoCodes .= Html::tag('li', Html::tag('span', 'Used Code', Code::getUsedHTMLOptions()));
$demoCodes .= Html::endTag('ul');

echo GridView::widget([
    'id'=>'members-gridview',
    'dataProvider' => $memberDataProvider,
    'filterModel' => $memberSearchModel,
    'showFooter' => true,
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
        'ContactsCount', // Is this column needed? Members should not be able to have 0 contacts.
        [
            'attribute' => 'codes.code_status',
            'label' => Yii::t('app', 'Voting Code'),
            'format' => 'raw',
            'footer' => $demoCodes,
            'filter' => [Code::CODE_STATUS_INVALID_UNUSED => 'Invalid Code', Code::CODE_STATUS_UNUSED => 'Unused Code', Code::CODE_STATUS_USED => 'Used Code'],
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
