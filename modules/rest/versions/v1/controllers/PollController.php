<?php

namespace app\modules\rest\versions\v1\controllers;

use Yii;
use app\models\Poll;
use app\models\Code;
use app\modules\rest\controllers\VotingRestController;
use yii\helpers\ArrayHelper;
use app\components\filters\TokenFilter;
use yii\base\UserException;

class PollController extends VotingRestController
{
    public $modelClass = 'app\models\Poll';

    public function actionGet()
    {
        $token = Yii::$app->request->get('token'); // Better way to get this?

        $code = Code::findCodeByToken($token);
        if (!$code || !$code->isValid()) {
            throw new UserException(Yii::t('app', 'Invalid voting code'));
        } elseif ($code->isUsed()) {
            throw new UserException(Yii::t('app', 'This voting code has already been used'));
        }

        $poll = $code->getPoll()->with(['options', 'organizer'])->one();
        $options = $poll->getOptions()->all();
        $organizer = $poll->getOrganizer()->one();
        $pollFields = ['title', 'question', 'info', 'select_min', 'select_max', 'start_time', 'end_time'];

        $data = ArrayHelper::merge($poll->toArray($pollFields), ['options' => ArrayHelper::getColumn($options, function($option) {
            $optionFields = ['id', 'text'];
            return $option->toArray($optionFields);
        }), 'organizer' => $organizer->toArray(['name', 'email'])]);
        return $data;
    }
}
