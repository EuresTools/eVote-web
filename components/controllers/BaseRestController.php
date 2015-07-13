<?php

namespace app\components\controllers;

use Yii;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use app\models\User;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;

class BaseRestController extends ActiveController
{

    public function behaviors()
    {

        $behaviors = parent::behaviors();

        /*
        // test with basic auth which can be set in params
        $behaviors['authenticator'] = [
        'class' => HttpBasicAuth::className(),
        'auth'  => function ($username, $password) {
            if ($username==\Yii::$app->params['HttpBasicAuth']['username'] && $password==\Yii::$app->params['HttpBasicAuth']['password']) {
                return new User();
            } else {
                return null;
            }
        }];
        */

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBasicAuth::className(),  // login with http auth currently username = access_token check
                QueryParamAuth::className(), // login with url get parameter ?access_token=tokenvalue
//                HttpBearerAuth::className(),
            ],
        ];

        /*
        //set response header to application/json only
        $behaviors['contentNegotiator'] = [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
        //            'application/xml' => Response::FORMAT_XML,
                ],
        ];
        */
        return $behaviors;
    }
}
