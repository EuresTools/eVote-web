<?php

namespace app\components\filters;

use Yii;
use Yii\base\ActionFilter;
use app\models\Code;
use app\models\FailedAttempt;
use yii\web\HttpException;
use yii\base\UserException;

class TokenFilter extends ActionFilter {

    public $tokenParam = 'token';

    public function beforeAction($action)
    {
        $request = Yii::$app->getRequest();
        $response = Yii::$app->getResponse();

        if ($this->shouldBlockIP($request)) {
            // The IP is spamming invalid codes and should be blocked.
            $this->handleBlocked($response);
        }

        $token = $request->get($this->tokenParam);
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

    private function shouldBlockIP($request)
    {
        $ip_address = $request->getUserIP();
        $threeHours = new \DateInterval('PT3H');
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $threeHoursAgo = (new \DateTime('now', new \DateTimeZone('UTC')))->sub($threeHours);
        $models = FailedAttempt::find()->where('ip_address = :ip and time > :time', [':ip' => $ip_address, ':time' => $threeHoursAgo->format('Y-m-d H:i:s')])->limit(3)->all();
        if (count($models) === 3) {
            return true;
        }
        return false;
    }


    private function handleNoToken($response)
    {
        throw new UserException(Yii::t('app', 'No voting code provided.'));
    }

    private function handleInvalid($request, $response)
    {
        // Log the failed attempt in the database.
        $token = $request->get($this->tokenParam);
        if (!is_string($token)) {
            $token = null;
        }
        $attempt = new FailedAttempt();
        $attempt->token = $token;
        $attempt->ip_address = $request->getUserIP();
        if ($attempt->validate()) {
            $attempt->save(false);
        }
        throw new UserException(Yii::t('app', 'Invalid voting code'));
    }


    private function handleUsed($response)
    {
        throw new UserException(Yii::t('app', 'This voting code has already been used.'));
    }

    private function handleBlocked($response)
    {
        throw new UserException(Yii::t('app', 'You have submitted too many invalid voting codes. Your IP address has been temporarily blocked.'));
    }

    private function handleSuccess($code)
    {
        $poll = $code->getPoll()->one();
        if (!$poll->isLocked()) {
            $poll->lock();
            $poll->save();
        }
    }
}
