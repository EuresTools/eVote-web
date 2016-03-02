<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\db\Query;

Modal::begin([
    'id' => !empty($target)? $target : 'contactEmailsModal',
    'header' => Html::tag('h4', Yii::t('app', 'Get Contact Emails'), ['class'=>'modal-title']),
]);

/*
//get the contacts
$contacts = $model->getContacts()->all();

//get the email of all contacts as array
$contactEmails=ArrayHelper::getColumn($contacts, 'email');

$contactEmailsText = implode("\n", $contactEmails);
echo Html::textarea('emailContacts', $contactEmailsText, $options = ['style'=>'width:100%;height:500px;']);
*/

// select the count of contacts which is needed for group_concat_max_len (count of contacts * email max lenght (255)) default value is only 1024 chars
$contactsCount = (new \yii\db\Query())
 ->select('count(*)')
 ->from('poll p')
 ->leftJoin('member m', 'p.id=m.poll_id')
 ->leftJoin('contact c', 'm.id=c.member_id')
 ->where(['p.id' => $model->id])
 ->scalar();
 //print_pre($contactsCount, 'contactsCount', false);


$command = Yii::$app->db->createCommand('SET group_concat_max_len = :maxlength');
$command->bindValue(':maxlength', $contactsCount * 255);
//$command->bindValue(':maxlength', 1024);
$command->execute();


// select the emails and concat them
$query = new Query;
$query->select(['GROUP_CONCAT(concat(c.email) SEPARATOR "\n") AS emails'])
    ->from('poll p')
    ->leftJoin('member m', 'p.id=m.poll_id')
    ->leftJoin('contact c', 'm.id=c.member_id')
    ->where(['p.id' => $model->id])
    ->limit(-1);
$command = $query->createCommand();
$contactEmailsText = $command->queryScalar();
echo Html::textarea('emailContacts', $contactEmailsText, $options = ['style'=>'width:100%;height:500px;']);

Modal::end();
