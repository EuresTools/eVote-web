<?php

namespace app\components\filters;

use Yii;
use Yii\base\ActionFilter;
use app\models\Code;
use app\models\FailedAttempt;
use yii\web\HttpException;
use yii\base\UserException;

class BaseFilter extends ActionFilter {

    public $tokenParam = 'token';

    protected function getToken()
    {
        $request = Yii::$app->getRequest();
        $token = Yii::$app->getSession()->get($this->tokenParam, null);
        if ($token === null) {
            $token = $request->get($this->tokenParam);
        }
        if ($token === null) {
            $token = $request->post('TokenInputForm')['token'];
        }
        return $token;
    }
}
