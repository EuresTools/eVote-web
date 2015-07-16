<?php

namespace app\controllers;

use Yii;
use app\models\Code;
use app\models\Member;
use app\models\search\CodeSearch;
use app\components\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\helpers\PollUrl;

/**
 * CodeController implements the CRUD actions for Code model.
 */
class CodeController extends BaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'invalidate' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Code models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CodeSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Code model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Code model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($poll_id, $member_id)
    {
        $member = Member::findOne($member_id);
        if($member && !$member->hasValidCode()) {
            Code::generateCode($poll_id, $member_id)->save();
        } else {
            Yii::$app->getSession()->setFlash('error', 'This member already has a valid voting code');
        }
        return $this->redirect(PollUrl::toRoute(['member/view', 'poll_id' => $poll_id, 'id' => $member_id]));
    }

    public function actionInvalidate($id)
    {
        $code = $this->findModel($id);
        if ($code->isValid()) {
            if ($code->isUsed()) {
                $code->code_status = Code::CODE_STATUS_INVALID_USED;
            } else {
                $code->code_status = Code::CODE_STATUS_INVALID_UNUSED;
            }
            $code->save();
        }
        return $this->redirect(PollUrl::toRoute(['member/view', 'poll_id' => $code->poll_id, 'id' => $code->member_id]));
    }

    /**
     * Updates an existing Code model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Code model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Code model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Code the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Code::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
