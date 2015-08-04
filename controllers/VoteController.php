<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use app\models\Code;
use app\models\Vote;
use app\models\VoteOption;
use app\models\forms\TokenInputForm;
use app\models\forms\VotingForm;
use app\components\controllers\BaseController;
use app\components\filters\TokenFilter;
use app\components\filters\OpenPollFilter;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
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
    private $_token = null;


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','voting'],
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
            'tokenFilter' => [
               'class' => TokenFilter::className(),
               'except' => ['index'],
            ],
            'openPollFilter' => [
               'class' => openPollFilter::className(),
               'except' => ['index'],
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

            // store the used token in a session? to able to use it when submitting the form?
            Yii::$app->session->set('token', $model->token);
            return $this->redirect(['voting']);
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }


    public function actionVoting()
    {
        $token = Yii::$app->session->get('token', null);
        if (!$token) {
            return $this->redirect(['index']);
        } else {
            // display the form from the poll options
            // get code through the token
            $code = Code::findCodeByToken($token);
            // check again if its not used etc.
            if ($code->checkCode()) {
                // display the form when code is not used and valid
                $model = new VotingForm($code);
                $success = false;
                if (Yii::$app->request->post($model->formName())) {
                    $model->load(Yii::$app->request->post());
                    if ($model->validate()) {
                        // save the vote and selected options
                        $transaction = \Yii::$app->db->beginTransaction();
                        try {
                            $vote = new Vote();
                            $vote->code_id = $code->id;
                            if ($vote->save()) {
                                // save selected options
                                foreach ($model->options as $optionId) {
                                    $option = $model->getOptionById($optionId);
                                    $vote->link('options', $option);
                                    /*if (!$vote->link('options', $option)) {
                                        throw new \Exception("Option couldn't be linked to vote", 1);
                                    }*/
                                }
                                $code->code_status = Code::CODE_STATUS_USED;
                                if (!$code->save()) {
                                    if ($code->getErrors()) {
                                        Yii::$app->getSession()->addFlash('error', Html::errorSummary($code, $options = ['header'=>'Failed to save due to error:']));
                                    }
                                    throw new \Exception("Code Couldn't be saved ", 1);
                                }
                            } else {
                                if ($vote->getErrors()) {
                                    Yii::$app->getSession()->addFlash('error', Html::errorSummary($vote, $options = ['header'=>'Failed to save due to error:']));
                                }
                                throw new \Exception("Vote Couldn't be saved ", 1);
                            }
                            $transaction->commit();
                            $success = true;
                        } catch (\Exception $e) {
                            $transaction->rollBack();
                            Yii::warning('There was an error on saving a vote: '.$e->getMessage());
                            if (!Yii::$app->getSession()->hasFlash('error')) {
                                Yii::$app->getSession()->addFlash('error', $e->getMessage());
                            }
                            //throw new HttpException(400, 'There was an error on saving a vote: '.$e->getMessage());
                        }
                    }
                }
                if ($success) {
                    // remove the token
                    Yii::$app->session->remove('token');
                    return $this->render('voting_success');
                } else {
                    return $this->render('voting', ['show_form'=>true, 'model'=>$model]);
                }
            } else {
                Yii::$app->session->remove('token');
                Yii::$app->getSession()->setFlash('token-error', $code->getErrors('token')[0]);
            }
        }
        return $this->render('voting', ['show_form'=>false, 'model'=>null]);
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
