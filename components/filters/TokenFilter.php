<?php

namespace app\components\filters;

use Yii;
use Yii\base\ActionFilter;
use app\models\Code;
use app\models\FailedAttempt;
use yii\web\HttpException;
use yii\base\UserException;

class TokenFilter extends BaseFilter {

    public function beforeAction($action)
    {
        $request = Yii::$app->getRequest();
        $response = Yii::$app->getResponse();

        $token = $this->getToken();

        if ($token === null) {
            // Query parameter not provided.
            $this->handleNoToken($response);
        }

        if (!is_string($token)) {
            // Value is not a string for whatever reason.
            $this->handleInvalid($request, $response);
        }

        $code = Code::findCodeByToken($token, get_class($this));

        if ($code === null || !$code->isValid()) {
            // The code is not valid.
            $this->handleInvalid($request, $response);
        } elseif ($code->isUsed()) {
            // The code has already been used.
            $this->handleUsed($response);
        }
        $this->handleSuccess($code);
        return true;
    }

    private function handleNoToken($response)
    {
        throw new UserException(Yii::t('app', 'No voting code provided.'));
    }

    private function handleInvalid($request, $response)
    {
        throw new UserException(Yii::t('app', 'Invalid voting code'));
    }


    private function handleUsed($response)
    {
        throw new UserException(Yii::t('app', 'This voting code has already been used.'));
    }

    private function handleSuccess($code)
    {
        $poll = $code->getPoll()->one();

        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $startTime = new \DateTime($poll->start_time, new \DateTimeZone('UTC'));
        $endTime = new \DateTime($poll->end_time, new \DateTimeZone('UTC'));
        // only lock Poll if it also at least started.
        if (!$poll->isLocked() && ($now >= $startTime) && ($now <= $endTime)) {
            $poll->lock();
            $poll->save();
        }
    }
}
