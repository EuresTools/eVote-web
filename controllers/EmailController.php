<?php

namespace app\controllers;

use Yii;
use app\models\Member;
use app\models\Code;
use app\models\Poll;
use app\components\controllers\PollDependedController;
use yii\helpers\ArrayHelper;
use app\models\forms\EmailForm;
use yii\filters\AccessControl;
use app\components\filters\OrganizationAccessRule;

class EmailController extends PollDependedController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'ruleConfig' => ['class' => OrganizationAccessRule::className(), 'modelClass'=> Poll::className(), 'queryParam'=>'poll_id'],
            ],
        ];
    }

    // Sends an email to multiple members.
    public function actionSendmultiple()
    {
        $poll = $this->getPoll();

        $email = new EmailForm();
        if ($email->load(Yii::$app->request->post())) {
            $members = null;
            if ($email->sendMode == EmailForm::EMAIL_TO_ALL) {
                $members = Member::find()->where($this->getPollSearchOptions())->with('codes')->with('contacts')->all();
            } elseif ($email->sendMode == EmailForm::EMAIL_TO_UNUSED) {
                $codes = Code::find()->where($this->getPollSearchOptions())->valid()->unused()->with('member.contacts')->all();
                $members = ArrayHelper::getColumn($codes, 'member');
            } elseif ($email->sendMode == EmailForm::EMAIL_TO_USED) {
                $codes = Code::find()->where($this->getPollSearchOptions())->valid()->used()->with('member.contacts')->all();
                $members = ArrayHelper::getColumn($codes, 'member');
            }

            // Count successful and failed emails.
            $success = 0;
            $failure = 0;

            foreach ($members as $member) {
                if ($this->sendEmailToMember($email, $member)) {
                    $success++;
                } else {
                    $failure++;
                }
            }
        }
        if ($success > 0) {
            Yii::$app->getSession()->addFlash('success', Yii::t('app', 'Successfully sent {n, plural, =0{no Email} =1{one Email} other{# Emails}}!', ['n' => $success]));
        }
        if ($failure > 0) {
            Yii::$app->getSession()->addFlash('error', Yii::t('app', 'Failed to send {n, plural, =0{no Email} =1{one Email} other{# Emails}}!', ['n' =>$failure]));
        }
        return $this->redirect(['poll/view', 'id'=>$poll->id, 'tab'=>'members']);
    }

    // Sends an email to a single member.
    public function actionSendsingle($member_id)
    {
        $member = Member::find()->primary_key($member_id)->poll_searchOptions($this->getPollSearchOptions())->one();

        if ($member === null) {
            throw new NotFoundHttpException(Yii::t('app/error', 'The requested page does not exist.'));
        }

        $email = new EmailForm();
        if ($email->load(Yii::$app->request->post())) {
            if ($this->sendEmailToMember($email, $member)) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Email sent successfully.'));
            } else {
                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Failed to send email.'));
            }
        }
        return $this->redirect(['member/view', 'id' => $member->id]);
    }

    private function sendEmailToMember($email, $member)
    {
        $poll = $this->getPoll();
        $organizer = $poll->organizer;
        if ($member->contacts) {
            $subject = $this->resolveTags($email->subject, $member);
            $message = $this->resolveTags($email->message, $member);
            $mail = Yii::$app->mailer->compose()
                ->setFrom([$organizer->email => $organizer->name])
                ->setTo(ArrayHelper::getColumn($member->contacts, 'email'))
                ->setReplyTo([$organizer->email => $organizer->name])
                ->setSubject($subject)
                ->setTextBody($message);
            return $mail->send();
        }
        return false;
    }

    private function resolveTags($string, $member)
    {
        $string = str_replace('<member-name>', $member->name, $string);
        $string = str_replace('<member-group>', $member->group, $string);
        $validCode = $member->getValidCode();
        if (strpos($string, '<voting-code>') !== false) {
            if ($validCode) {
                $string = str_replace('<voting-code>', $validCode->getTokenForEmail(), $string);
            } else {
                $string = str_replace('<voting-code>', 'no valid '.Yii::t('app', 'token').' contact support!', $string);
            }
        }
        return $string;
    }
}
