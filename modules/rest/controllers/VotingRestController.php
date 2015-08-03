<?php

namespace app\modules\rest\controllers;

use Yii;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use app\components\filters\TokenFilter;
use app\components\filters\OpenPollFilter;
use yii\filters\VerbFilter;

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
//                    'application/xml' => Response::FORMAT_XML,
                ],
        ];



        $behaviors['tokenFilter'] = [
            'class' => TokenFilter::className(),
        ];

        $behaviors['openPollFilter'] = [
            'class' => OpenPollFilter::className(),
        ];


        // $behaviors['verbs'] = [
        //     'class' => VerbFilter::className(),
        //     'actions' => [
        //         'submit' => ['get'],
        //         'get' => ['get'],
        //     ],
        // ];


        return $behaviors;
    }

    public function beforeAction($action)
    {
        \Yii::$app->response->on(Response::EVENT_BEFORE_SEND, [$this, 'modifyResponse']);
        if (!parent::beforeAction($action)) {
            return false;
        }
        return true; // or false to not run the action
    }



    public function modifyResponse($event)
    {
        $response = $event->sender;

        if ($response->isSuccessful) {
            $response->data = ['success' =>  $response->isSuccessful, 'data' => $response->data];

        } else {
            $response->data = ['success' =>  $response->isSuccessful, 'error' => ['message' => $response->data['message']]];
            $response->statusCode = 200;
        }
    }
}
