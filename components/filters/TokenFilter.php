<?php

namespace app\components\filters;

use Yii;
use Yii\base\ActionFilter;
use app\models\Code;
use app\models\FailedAttempt;

class TokenFilter extends ActionFilter {

    public $tokenParam = 'token';

    public function beforeAction($action)
    {
        $request = Yii::$app->getRequest();
        $response = Yii::$app->getResponse();

        if ($this->shouldBlockIP($request)) {
            // The IP is spamming invalid codes and should be blocked.
            $this->handleBlocked($response);
            return false;
        }

        $token = $request->get($this->tokenParam);
        if ($token === null) {
            // Query parameter not provided.
            $this->handleNoToken($response);
            return false;
        }
        if (!is_string($token)) {
            // Value is not a string for whatever reason.
            $this->handleInvalid($request, $response);
            return false;
        }

        $code = Code::findCodeByToken($token, get_class($this));

        if ($code === null || !$code->isValid()) {
            // The code is not valid.
            $this->handleInvalid($request, $response);
            return false;
        } elseif ($code->isUsed()) {
            // The code has already been used.
            $this->handleUsed($response);
            return false;
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
        $response->data = ['success' => false, 'error' => ['message' => 'No voting code provided.']];
        //$response->statusCode = 401;
    }

    private function handleInvalid($request, $response)
    {
        $response->data = ['success' => false, 'error' => ['message' => 'Invalid voting code.']];
        //$response->statusCode = 403;

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
    }

    private function handleUsed($response)
    {
        $response->data = ['success' => false, 'error' => ['message' => 'This voting code has already been used.']];
        //$response->statusCode = 403;
    }

    private function handleBlocked($response)
    {
        $response->data = ['success' => false, 'error' => ['message' => 'You have submitted too many invalid voting codes. Your IP address has been temporarily blocked.']];
        //$response->statusCode = 403;
    }

    private function handleSuccess($code) {
        $poll = $code->getPoll()->one();
        if(!$poll->isLocked()) {
            $poll->lock();
            $poll->save();
        }
    }
}
