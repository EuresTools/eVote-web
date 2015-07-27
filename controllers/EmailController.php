<?php

namespace app\controllers;

use Yii;
use app\models\Member;
use app\models\Code;
use app\components\controllers\PollDependedController;
use yii\helpers\ArrayHelper;
use app\models\forms\EmailForm;


class EmailController extends PollDependedController {

    public function actionSend() {
        $email = new EmailForm();
        if ($email->load(Yii::$app->request->post())) {
            $members = null;
            if ($email->sendMode == EmailForm::EMAIL_TO_ALL) {
                $members = Member::find()->where($this->getPollSearchOptions())->with('codes')->with('contacts')->all();
            }
            else if ($email->sendMode == EmailForm::EMAIL_TO_UNUSED) {
                $codes = Code::find()->where($this->getPollSearchOptions())->valid()->unused()->with('member.contacts')->all();
                $members = ArrayHelper::getColumn($codes, 'member');
            }
            else if ($email->sendMode == EmailForm::EMAIL_TO_USED) {
                $codes = Code::find()->where($this->getPollSearchOptions())->valid()->used()->with('member.contacts')->all();
                $members = ArrayHelper::getColumn($codes, 'member');
            }

            $poll = $this->getPoll();
            $organizer = $poll->organizer;

            foreach($members as $member) {
                $subject = $this->resolveTags($email->subject, $member);
                $message = $this->resolveTags($email->message, $member);
                Yii::$app->mailer->compose()
                    ->setFrom([$organizer->email => $organizer->name])
                    ->setTo(ArrayHelper::getColumn($member->contacts, 'email'))
                    ->setReplyTo([$organizer->email => $organizer->name])
                    ->setSubject($subject)
                    ->setTextBody($message)
                    ->send();
            }
        }
        return $this->redirect(['poll/view', 'id' => $poll->id]);
    }

    private function resolveTags($string, $member) {
        $string = str_replace('<member-name>', $member->name, $string);
        $string = str_replace('<member-group>', $member->group, $string);
        $string = str_replace('<voting-code>', $member->getValidUnusedCode(), $string);
        return $string;
    }
}
