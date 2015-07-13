<?php

namespace app\modules\rest\versions\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Poll;
use yii\web\Response;

class PollController extends ActiveController
{
    public $modelClass = 'app\models\Poll';

    /**
    * disable session for REST-Request
    * no loginUrl required
    */
    public function init2()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
        \Yii::$app->user->loginUrl = null;
    }
}
