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
        if(!$code || !$code->isValid() || $code->isUsed()) {
            return ['success' => false, 'error' => 'Invalid voting code'];
        }
        $poll = $code->getPoll()->with(['options' => function($q) {
            $q->select(['id', 'text']);
        }])->select(['title', 'question', 'select_min', 'select_max', 'start_time', 'end_time'])->asArray()->one();
        return $poll;
    }
}
