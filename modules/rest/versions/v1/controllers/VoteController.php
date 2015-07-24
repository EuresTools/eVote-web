<?php

namespace app\modules\rest\versions\v1\controllers;

use Yii;
use app\models\Poll;
use app\models\Code;
use app\models\Vote;
use app\modules\rest\controllers\VotingRestController;
use yii\helpers\ArrayHelper;

class VoteController extends VotingRestController
{
    public $modelClass = 'app\models\Vote';

    public function actionSubmit() {
        $token = Yii::$app->request->get('token'); // Better way to get this?
        $code = Code::findCodeByToken($token);
        if(!$code || !$code->isValid()) {
            return ['success' => false, 'error' => ['message' => 'Invalid voting code']];
        }
        else if ($code->isUsed()) {
            return ['success' => false, 'error' => ['message' => 'This voting code has already been used']];
        }
        $poll = $code->getPoll()->with('options')->one();
        $data = Yii::$app->request->getBodyParams();
        $optionIDs = $data['options'];
        if ($optionIDs === null || !is_array($optionIDs)) {
            return ['success' => false, 'error' => ['message' => 'Bad Request']];
        }

        if(count($optionIDs) < $poll->select_min) {
            return ['success' => false, 'error' => ['message' => 'Too few options selected']];
        }
        if(count($optionIDs) > $poll->select_max) {
            return ['success' => false, 'error' => ['message' => 'Too many options selected']];
        }

        $transaction = Yii::$app->db->beginTransaction();
        $vote = new Vote();
        $vote->code_id = $code->id;

        if(!$vote->save()) {
            return ['success' => false, 'error' => ['message' => 'Something went wrong']];
        }

        foreach($optionIDs as $optionId) {
            $option = $poll->getOptions()->where(['id' => $optionId])->one();
            if(!$option) {
                $transaction->rollBack();
                return ['success' => false, 'error' => ['message' => 'Invalid option']];
            }

            try {
                $vote->link('options', $option);
            } catch (Exception $e) {
                $transaction->rollBack();
                return ['success' => false, 'error' => ['message' => 'Something went wrong']];
            }
        }
        $code->code_status = Code::CODE_STATUS_USED;
        if (!$code->save()) {
            $transaction->rollBack();
            return ['success' => false, 'error' => ['message' => 'Something went wrong']];
        }
        $transaction->commit();

        // Log the vote in the vote log file.
        $arrayString = implode(", ", $optionIDs);
        $arrayString = "[$arrayString]";
        Yii::info("$code->token $arrayString", 'vote');

        return ['success' => true, 'data' => $data];
    }
}
