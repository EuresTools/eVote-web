<?php

namespace app\modules\rest\controllers;

use Yii;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use app\components\filters\TokenFilter;
use app\components\filters\OpenPollFilter;

class VotingRestController extends Controller
{

    public function behaviors()
    {

        $behaviors = parent::behaviors();

        //set response header to application/json only
        $behaviors['contentNegotiator'] = [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
        //            'application/xml' => Response::FORMAT_XML,
                ],
        ];


        $behaviors['tokenFilter'] = [
            'class' => TokenFilter::className(),
        ];

        $behaviors['openPollFilter'] = [
            'class' => OpenPollFilter::className(),
        ];

        return $behaviors;
    }
}
