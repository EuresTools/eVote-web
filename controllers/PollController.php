<?php

namespace app\controllers;

use Yii;
use app\models\Poll;
use app\models\Option;
use app\models\search\PollSearch;
use app\models\search\MemberSearch;
use app\components\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\components\base\Model;
use yii\helpers\ArrayHelper;

/**
 * PollController implements the CRUD actions for Poll model.
 */
class PollController extends BaseController
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
        $searchModel = new PollSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Poll model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $memberSearchModel = new MemberSearch();
        // Only display the members for this poll.
        $params = Yii::$app->request->queryParams;
        $params[$memberSearchModel->formName()]['poll_id'] = $id;
        $memberDataProvider = $memberSearchModel->search($params);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'memberSearchModel' => $memberSearchModel,
            'memberDataProvider' => $memberDataProvider
        ]);
    }

    /**
     * Creates a new Poll model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Poll();
        $modelOptions = [new Option(),new Option()];

        if ($model->load(Yii::$app->request->post())) {

            $modelOptions = Model::createMultiple(Option::classname());
            Model::loadMultiple($modelOptions, Yii::$app->request->post());

            //$OptionsAttributeToValidate=array_keys($modelOptions[0]->getAttributes(null, $except = ['poll_id']));

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    //ActiveForm::validateMultiple($modelOptions, $OptionsAttributeToValidate),
                    //ActiveForm::validate($model, array_keys($model->getAttributes(null, $except = ['organizer_id'])))
                    ActiveForm::validateMultiple($modelOptions),
                    ActiveForm::validate($model)
                );
            }

             // validate all models
            // $valid = $model->validate(array_keys($model->getAttributes(null, $except = ['organizer_id'])));
            // $valid = Model::validateMultiple($modelOptions, $OptionsAttributeToValidate) && $valid;

            $valid = $model->validate();
            $valid = Model::validateMultiple($modelOptions) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        foreach ($modelOptions as $modelOption) {
                            $modelOption->poll_id = $model->id;
                            if (! ($flag = $modelOption->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
        return $this->render('create', ['model' => $model, 'modelOptions' => $modelOptions]);
    }

    /**
     * Updates an existing Poll model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $modelOptions = $model->options;

        if ($model->load(Yii::$app->request->post())) {

            $oldIDs = ArrayHelper::map($modelOptions, 'id', 'id');
            $modelOptions = Model::createMultiple(Option::classname(), $modelOptions);
            Model::loadMultiple($modelOptions, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelOptions, 'id', 'id')));

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelOptions),
                    ActiveForm::validate($model)
                );
            }

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelOptions) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs)) {
                            Option::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelOptions as $modelOption) {
                            $modelOption->poll_id = $model->id;
                            if (! ($flag = $modelOption->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
        return $this->render('update', [
            'model' => $model,
            'modelOptions' => (empty($modelOptions)) ? [new Option(), new Option()] : $modelOptions
        ]);
    }


    /**
     * Deletes an existing Poll model.
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
