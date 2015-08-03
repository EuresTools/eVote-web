<?php

namespace app\modules\rest\versions\v1\controllers;

use Yii;
use app\models\Poll;
use app\models\Code;
use app\models\Vote;
use app\modules\rest\controllers\VotingRestController;
use yii\helpers\ArrayHelper;
use yii\base\UserException;

class VoteController extends VotingRestController
{
    public $modelClass = 'app\models\Vote';

    public function actionSubmit()
    {
        $token = Yii::$app->request->get('token'); // Better way to get this?
        $code = Code::findCodeByToken($token);
        if (!$code || !$code->isValid()) {
            throw new UserException(Yii::t('app', 'Invalid voting code'));
        } elseif ($code->isUsed()) {
            throw new UserException(Yii::t('app', 'This voting code has already been used'));
        }
        $poll = $code->getPoll()->with('options')->one();
        $data = Yii::$app->request->getBodyParams();
        $optionIDs = $data['options'];
        if ($optionIDs === null || !is_array($optionIDs)) {
            throw new UserException(Yii::t('app', 'Bad Request'));
        }

        if (count($optionIDs) < $poll->select_min) {
            throw new UserException(Yii::t('app', 'Too few options selected'));
        }
        if (count($optionIDs) > $poll->select_max) {
            throw new UserException(Yii::t('app', 'Too many options selected'));
        }

        $transaction = Yii::$app->db->beginTransaction();
        $vote = new Vote();
        $vote->code_id = $code->id;

        if (!$vote->save()) {
            throw new UserException(Yii::t('app', 'Something went wrong'));
        }

        foreach ($optionIDs as $optionId) {
            $option = $poll->getOptions()->where(['id' => $optionId])->one();
            if (!$option) {
                $transaction->rollBack();
                throw new UserException(Yii::t('app', 'Invalid option'));
            }

            try {
                $vote->link('options', $option);
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new UserException(Yii::t('app', 'Something went wrong'));
            }
        }
        $code->code_status = Code::CODE_STATUS_USED;
        if (!$code->save()) {
            $transaction->rollBack();
            throw new UserException(Yii::t('app', 'Something went wrong'));
        }
        $transaction->commit();

        // Log the vote in the vote log file.
        $arrayString = implode(", ", $optionIDs);
        $arrayString = "[$arrayString]";
        Yii::info("$code->token $arrayString", 'vote');
        return $data;
    }
}
