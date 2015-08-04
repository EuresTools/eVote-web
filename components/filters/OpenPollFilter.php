<?php

namespace app\components\filters;

use Yii;
use Yii\base\ActionFilter;
use app\models\Code;
use app\models\FailedAttempt;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\base\UserException;

class OpenPollFilter extends BaseFilter
{

    // At this point we assume that the TokenFilter has been applied and that
    // the token is valid.
    public function beforeAction($action)
    {
        $request = Yii::$app->getRequest();
        $response = Yii::$app->getResponse();

        $token = $this->getToken();
        // if (!$token) {
        //     throw new HttpException(400, Yii::t('app', 'No Token given.'));
        // }
        $code = Code::findCodeByToken($token);
        // if (!$code) {
        //     throw new NotFoundHttpException(Yii::t('app', 'Code not found.'));
        // }
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

    private function handleNotStarted($response)
    {
        throw new UserException(Yii::t('app', 'The requested poll has not started yet.'));
    }

    private function handleOver($response)
    {
        throw new UserException(Yii::t('app', 'The requested poll has ended.'));
    }
}
