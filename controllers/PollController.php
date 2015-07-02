<?php

namespace app\controllers;

use Yii;
use app\models\Poll;
use app\models\PollSearch;
use app\models\Option;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;

/**
 * PollController implements the CRUD actions for Poll model.
 */
class PollController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Poll models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PollSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Poll model.
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
     * Creates a new Poll model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        //$model = new Poll();

        //if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);
        //} else {
            //return $this->render('create', [
                //'poll' => $model,
            //]);
        //}


        $poll = new Poll();
        $poll->organizer_id = Yii::$app->user->identity->getOrganizer()->one()->getPrimaryKey();
        $optionCount = count(Yii::$app->request->post('Option'));
        $options = [new Option(), new Option()];
        for ($i = 2; $i < $optionCount; $i++) {
            $options[] = (new Option());
        }

        if ($poll->load(Yii::$app->request->post()) && Model::loadMultiple($options, Yii::$app->request->post())) { 
            $transaction = Yii::$app->db->beginTransaction();
            if ($poll->save()) {
                foreach ($options as $option) {
                    $option->poll_id = $poll->id;
                    if (!$option->save()) {
                        Yii::trace("$option->poll_id", "poll id");
                        $transaction->rollback();
                        return $this->render('create', ['poll' => $poll, 'options' => $options]);
                    }
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $poll->id]);
            }
            $transaction->rollback();
        }
        return $this->render('create', ['poll' => $poll, 'options' => $options]);
    }

    /**
     * Updates an existing Poll model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {

        $poll = $this->findModel($id);
        $oldOptions = $poll->getOptions()->all();
        $optionCount = count(Yii::$app->request->post('Option'));

        $options = [new Option(), new Option()];
        for ($i = 2; $i < $optionCount; $i++) {
            $options[] = (new Option());
        }

        if ($poll->load(Yii::$app->request->post()) && Model::loadMultiple($options, Yii::$app->request->post())) { 
            $transaction = Yii::$app->db->beginTransaction();
            foreach ($oldOptions as $option) {
                $option->delete();
            }

            if ($poll->save()) {
                foreach ($options as $option) {
                    $option->poll_id = $poll->id;
                    if (!$option->save()) {
                        $transaction->rollback();
                        return $this->render('update', ['poll' => $poll, 'options' => $options]);
                    }
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $poll->id]);
            }
            $transaction->rollback();
        }
        return $this->render('update', ['poll' => $poll, 'options' => $oldOptions]);

        //$model = $this->findModel($id);

        //if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);
        //} else {
            //return $this->render('update', [
                //'model' => $model,
            //]);
        //}
    }

    /**
     * Deletes an existing Poll model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $poll = $this->findModel($id);
        $options = $poll->getOptions()->all();
        foreach ($options as $option) {
            if($option->delete() === false) {
                $transaction->rollback();
                return $this->redirect(['index']);
            }
        }
        if($poll->delete() === false) {
            $transaction->rollback();
            return $this->redirect(['index']);
        }
        $transaction->commit();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Poll model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Poll the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Poll::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
