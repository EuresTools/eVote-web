<?php

namespace app\controllers;

use Yii;
use app\models\Poll;
use app\models\Option;
use app\models\Member;
use app\models\Code;
use app\models\search\PollSearch;
use app\models\search\MemberSearch;
use app\models\forms\EmailForm;
use app\components\base\Model;
use app\components\controllers\BaseController;
use app\components\filters\OrganizationAccessRule;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\widgets\ActiveForm;
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
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['update', 'view', 'delete', 'ajax-update'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'ruleConfig' => ['class' => OrganizationAccessRule::className(),],
            ],
        ];
    }

    public function actionAjaxUpdate()
    {
        $model = new Poll;
        // Check if there is an Editable ajax request
        if (isset($_POST['hasEditable'])) {
            $id = Yii::$app->request->post('editableKey');
            $model=$this->findModel($id);
            $model->setScenario('editable');

            $message='';
            // use Yii's response format to encode output as JSON
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            // fetch the first entry in posted data (there should
            // only be one entry anyway in this array for an
            // editable submission)
            // - $posted is the posted data for model without any indexes
            // - $post is the converted array for single model validation
            $post = [];
            $posted = current($_POST[$model->formName()]);
            $post[$model->formName()] = $posted;

            // read your posted model attributes
            if ($model->load($post)) {

                if (!$model->save()) {
                    $errors = \yii\helpers\Html::errorSummary($model);
                    $message.= Yii::t('app/error', 'Poll wasn\'t saved!{errors}', ['errors'=>$errors]);
                }

                // custom output to return to be displayed as the editable grid cell
                // data. Normally this is empty - whereby whatever value is edited by
                // in the input by user is updated automatically.
                $output = '';
                return ['output'=>$output, 'message'=>$message];
            } else {
                // else if nothing to do always return an empty JSON encoded output
                return ['output'=>'', 'message'=>''];
            }
        }
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
    public function actionView($id, $tab = null)
    {
        $memberSearchModel = new MemberSearch();
        // Only display the members for this poll.
        $params = Yii::$app->request->queryParams;
        $memberSearchModel->setAttribute('poll_id', $id);
        $memberDataProvider = $memberSearchModel->search($params);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'memberSearchModel' => $memberSearchModel,
            'memberDataProvider' => $memberDataProvider,
            'tab' => isset($tab) ? $tab : null,
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
        if ($model->isLocked()) {
            throw new HttpException(403, Yii::t('app', 'This poll cannot be edited because it has already been accessed by a voter'));
        }
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
            throw new NotFoundHttpException(Yii::t('app/error', 'The requested page does not exist.'));
        }
    }
}
