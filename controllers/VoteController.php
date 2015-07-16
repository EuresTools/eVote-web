<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;

use app\models\Code;
use app\models\forms\TokenInputForm;
use app\components\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


/**
 * VoteController implements all the actions which are used in the public voting
 */
class VoteController extends BaseController
{

    public $layout = 'voting';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    /**
     * Lists all Poll models.
     * @return mixed
     */
    public function actionIndex($token = null)
    {
        $model = new TokenInputForm();
        if (!empty($token)) {
            $model->token = Yii::$app->request->get('token');
            //$model->token = $token;
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // try {
            //     findCode($id)
            // } catch (Exception $e) {

            // }
            echo 'success';
            //return $this->goBack();
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Code model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Poll the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCode($id)
    {
        // todo: where can whe add the check vor a bruteforce attack
        if (($model = Code::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
