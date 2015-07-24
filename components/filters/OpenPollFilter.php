<?php

namespace app\components\filters;

use Yii;
use Yii\base\ActionFilter;
use app\models\Code;
use app\models\FailedAttempt;


class OpenPollFilter extends ActionFilter {

    public $tokenParam = 'token';

    // At this point we assume that the TokenFilter has been applied and that 
    // the token is valid.
    public function beforeAction($action) {
        $request = Yii::$app->getRequest();
        $response = Yii::$app->getResponse();

        $token = $request->get($this->tokenParam);
        $code = Code::findCodeByToken($token);
        $poll = $code->getPoll()->one();
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $startTime = new \DateTime($poll->start_time, new \DateTimeZone('UTC'));
        $endTime = new \DateTime($poll->end_time, new \DateTimeZone('UTC'));
        if ($now < $startTime) {
            $this->handleNotStarted($response);
            return false;
        }
        if ($now >= $endTime) {
            $this->handleOver($response);
            return false;
        }
        return true;
    }

    private function handleNotStarted($response) {
        $response->data = ['success' => false, 'error' => ['message' => 'The requested poll has not started yet.']];
    }

    private function handleOver($response) {
        $response->data = ['success' => false, 'error' => ['message' => 'The requested poll has ended.']];
    }
}
