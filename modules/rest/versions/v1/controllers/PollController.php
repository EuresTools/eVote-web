<?php

namespace app\modules\rest\versions\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Poll;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\filters\ContentNegotiator;

class PollController extends ActiveController
{
    public $modelClass = 'app\models\Poll';

    /*
    public function behaviors()
    {
        //set response header to application/json only
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
        //            'application/xml' => Response::FORMAT_XML,
                ],
            ],
        ];
    }*/


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
