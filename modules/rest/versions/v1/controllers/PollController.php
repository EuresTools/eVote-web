<?php

namespace app\modules\rest\versions\v1\controllers;

use Yii;
use app\models\Poll;
use app\models\Code;
use app\components\controllers\VotingRestController;
use yii\helpers\ArrayHelper;

class PollController extends VotingRestController
{
    public $modelClass = 'app\models\Poll';


    public function actionTest()
    {


        $items = Poll::find()->asArray()->all();
        //\Yii::$app->response->format = 'csv';
        $test=[
            'error'=> false,
            'result'=>$items,
        ];
        //return $items;
        return $test;
    }

    public function actionGet() {
        $token = Yii::$app->request->get('token'); // Better way to get this?
        $code = Code::findCodeByToken($token);
        if(!$code || !$code->isValid()) {
            return ['success' => false, 'error' => ['message' => 'Invalid voting code']];
        }
        else if ($code->isUsed()) {
            return ['success' => false, 'error' => ['message' => 'This voting code has already been used']];
        }

        $poll = $code->getPoll()->with(['options', 'organizer'])->one();
        $options = $poll->getOptions()->all();
        $organizer = $poll->getOrganizer()->one();
        $pollFields = ['title', 'question', 'select_min', 'select_max', 'start_time', 'end_time'];

        $json = ArrayHelper::merge($poll->toArray($pollFields), ['options' => ArrayHelper::getColumn($options, function($option) {
            $optionFields = ['id', 'text'];
            return $option->toArray($optionFields);
        }), 'organizer' => $organizer->toArray(['name', 'email'])]);
        return ['success' => true, 'data' => $json];
    }
}
