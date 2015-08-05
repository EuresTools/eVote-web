<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use app\models\forms\EmailForm;

Modal::begin([
    'id' => !empty($target)? $target : 'emailModal',
    'header' => Html::tag('h4', Yii::t('app', 'Send Email'), ['class'=>'modal-title']),
]);
$emailForm = new EmailForm();
$emailForm->poll = $model;
echo $this->render('_email_form', ['model' => $emailForm, 'member' => $model]);
Modal::end();
