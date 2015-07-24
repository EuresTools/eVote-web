<?php

namespace app\components\controllers;

use Yii;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\filters\ContentNegotiator;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;
use app\components\filters\TokenFilter;
use app\components\filters\OpenPollFilter;
//use app\components\filters\auth\QueryMemberTokenParamAuth;

class VotingRestController extends ActiveController
{

    public function behaviors()
    {

        $behaviors = parent::behaviors();

        //$behaviors['authenticator'] = [
            //'class' => CompositeAuth::className(),
            //'authMethods' => [
                //QueryMemberTokenParamAuth::className(), // login with url get parameter ?token=tokenvalue
            //],
        //];


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
