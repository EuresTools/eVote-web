<?php

namespace app\controllers;

use Yii;
use app\models\Member;
use app\models\Poll;
use app\models\Contact;
use app\models\search\MemberSearch;
use app\components\controllers\BaseController;
use app\components\controllers\PollDependedController;
use yii\filters\AccessControl;
use app\components\filters\OrganizationAccessRule;
use app\models\forms\UploadForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\UserException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use app\components\ExcelParser;
use app\components\base\Model;
use app\components\helpers\PollUrl;
use app\components\filters\ReturnUrlFilter;

/**
 * MemberController implements the CRUD actions for Member model.
 */
//class MemberController extends BaseController
class MemberController extends PollDependedController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'import' => ['post'],
                ],
            ],
            'returnUrl'=> [
                'class' => ReturnUrlFilter::className(),
                'only' => [
                    'update',
                    'create',
                    'delete',
                ]
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['update', 'view', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'ruleConfig' => ['class' => OrganizationAccessRule::className(), 'modelClass'=> Member::className()],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['import', 'index' , 'clear', 'create', 'update', 'view', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'ruleConfig' => ['class' => OrganizationAccessRule::className(), 'modelClass'=> Poll::className(), 'queryParam'=>'poll_id'],
            ],
        ];
    }

    /**
     * Lists all Member models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new MemberSearch();
        $this->setPollSearchOptions($searchModel);
        $params = Yii::$app->request->queryParams;

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Member model.
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
     * Creates a new Member model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Member();
        $this->setPollAttributes($model);
        $modelContacts = [new Contact()];

        if ($model->load(Yii::$app->request->post())) {
            Model::loadMultiple($modelContacts, Yii::$app->request->post());
            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelContacts),
                    ActiveForm::validate($model)
                );
            }

            $valid = $model->validate();
            $valid = Model::validateMultiple($modelContacts) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        foreach ($modelContacts as $modelContact) {
                            $modelContact->member_id = $model->id;
                            if (! ($flag = $modelContact->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        //return $this->redirect(['view', 'id' => $model->id]);
                        return $this->redirect($this->getReturnUrl(['view', 'id'=>$model->id]));
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
        return $this->render('create', [
            'model' => $model,
            'modelContacts' => $modelContacts,
        ]);
    }


    /* Parses an Excel file and creates multiple instances of the Member model.
        * If creation is successful, the browser will be redirected to the
        * 'index' page. */
    //public function actionImport()
    //{
        //$model = new UploadForm();

        //if (Yii::$app->request->isPost) {
            //$model->excelFile = UploadedFile::getInstance($model, 'excelFile');
            //$file = $model->upload();
            //if ($file) {
                //// Handle errors gracefully.
                //set_error_handler(function() {
                    //return false;
                //});
                //$member_dicts = ExcelParser::parseMembers($file->tempName);
                //// Restore the default error handler.
                //restore_error_handler();
                //$errors = [];
                //if (!$member_dicts) {
                    //$error= 'The file you selected could not be imported';
                    //$errors[] = $error;
                //} else {
                    //$transaction = Yii::$app->db->beginTransaction();
                    //foreach ($member_dicts as $dict) {
                        //$name = $dict['name'];
                        //$member = Member::find()->where(['name' => $name, 'poll_id' => $this->getPollId()])->one();
                        //$success = true;
                        //// If the member already exists, only re-import the contacts.
                        //if($member) {
                            //Contact::deleteAll('member_id = :member_id', [':member_id' => $member->id]);
                        //} else {
                            //$member = new Member();
                            //$this->setPollAttributes($member);
                            //$member->name = $dict['name'];
                            //$member->group = $dict['group'];
                            //$success = $member->save();
                        //}
                        //if($success) {
                            //foreach($dict['contacts'] as $contact_dict) {
                                //$contact = new Contact();
                                //$contact->member_id = $member->id;
                                //$contact->name = isset($contact_dict['name']) ? $contact_dict['name'] : null;
                                //$contact->email = filter_var($contact_dict['email'], FILTER_SANITIZE_EMAIL);
                                //if(!$contact->save()) {
                                    //$row = $contact_dict['row'];
                                    //$name = $contact_dict['name'];
                                    //$email = $contact_dict['email'];
                                    //$error = "Row $row: The contact $name ($email) could not be imported.";
                                    //$errors[] = $error;
                                //}
                            //}
                        //} else {
                            //$row = $dict['row'];
                            //$name = $dict['name'];
                            //$error = "Row $row: The member $name could not be imported.";
                            //$errors[] = $error;
                        //}
                    //}
                    //$transaction->commit();
                //}
                //foreach($errors as $error) {
                    //Yii::$app->getSession()->addFlash('warning', $error);
                //}
                //if($member_dicts) {
                    //return $this->redirect('index');
                //} else {
                    //return $this->render('import', ['model' => $model]);
                //}
            //}
        //}
        //return $this->render('import', [
            //'model' => $model,
        //]);

    //}


    public function actionImport()
    {
        $model = new UploadForm();

        $model->excelFile = UploadedFile::getInstance($model, 'excelFile');
        $file = $model->upload();

        if ($file) {
            try {
                // Handle errors gracefully.
                set_error_handler(function () {
                    return false;
                });
                $member_dicts = ExcelParser::parseMembers($file->tempName);
                // Restore the default error handler.
                restore_error_handler();
                $errors = [];
                if (!$member_dicts) {
                    $error =  Yii::t('app/error', 'The selected file could not be imported.');
                    $errors[] = $error;
                } else {
                    $transaction = Yii::$app->db->beginTransaction();
                    $poll = $this->getPoll();
                    // Delete all existing members.
                    // foreach ($poll->members as $member) {
                    //     $member->delete();
                    // }
                    // using new function to speed up the delete
                    $poll->deleteMemberData();

                    foreach ($member_dicts as $dict) {
                        $member = new Member();
                        $this->setPollAttributes($member);
                        $member->name = $dict['name'];
                        $member->group = $dict['group'];
                        if ($member->save()) {
                            foreach ($dict['contacts'] as $contact_dict) {
                                $contact = new Contact();
                                $contact->member_id = $member->id;
                                $contact->name = isset($contact_dict['name']) ? $contact_dict['name'] : null;
                                $contact->email = filter_var($contact_dict['email'], FILTER_SANITIZE_EMAIL);
                                if (!$contact->save()) {
                                    $row = $contact_dict['row'];
                                    $name = $contact_dict['name'];
                                    $email = $contact_dict['email'];
                                    $error =  Yii::t('app/error', 'Row {row}: The contact with name "{name}" and email "{email}" could not be imported.', ['row'=>$row, 'name'=>$name, 'email'=>$email]);
                                    $errors[] = $error;
                                }
                            }
                            if (empty($member->contacts)) {
                                $member->delete();
                                $row = $dict['row'];
                                $name = $dict['name'];
                                $error =  Yii::t('app/error', 'Row {row}: The member with name "{name}" could not be imported.', ['row'=>$row, 'name'=>$name]);
                                $errors[] = $error;
                            }
                        }
                    }
                    $transaction->commit();
                }
                foreach ($errors as $error) {
                    Yii::$app->getSession()->addFlash('import', $error);
                }
            } catch (Exception $e) {
                throw new Exception("Error Processing Request ". $e->getMessage(), 1);
            }
        } else {
            // todo : print error message in popup possible? e.g. on failed file upload?
            Yii::$app->getSession()->addFlash('import', $model->getErrors('excelFile')[0]);
        }
        return $this->redirect(['poll/view', 'id' => $this->getPollId(), 'tab' => 'members']);
    }

    /**
     * Updates an existing Member model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelContacts = $model->contacts;

        if ($model->load(Yii::$app->request->post())) {
            $oldIDs = ArrayHelper::map($modelContacts, 'id', 'id');
            $modelContacts = Model::createMultiple(Contact::classname(), $modelContacts);
            Model::loadMultiple($modelContacts, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelContacts, 'id', 'id')));

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::ValidateMultiple($modelContacts),
                    ActiveForm::validate($model)
                );
            }

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelContacts) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs)) {
                            Contact::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelContacts as $modelContact) {
                            $modelContact->member_id = $model->id;
                            if (! ($flag = $modelContact->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        //return $this->redirect(['view', 'id' => $model->id]);
                        return $this->redirect($this->getReturnUrl(['view', 'id'=>$model->id]));
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
        return $this->render('update', [
            'model' => $model,
            'modelContacts' => (empty($modelContacts)) ? [new Contact()] : $modelContacts
        ]);
    }

    /**
     * Deletes an existing Member model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        //return $this->redirect(['index']);
        return $this->redirect($this->getReturnUrl(['index']));
    }

    public function actionClear()
    {
        $poll = $this->getPoll();

        if (!$poll->deleteMemberData()) {
            throw new UserException(Yii::t('app/error', 'Error when deleting Member Data'));
        }
        // foreach ($poll->members as $member) {
        //     $member->delete();
        // }
        return $this->redirect(['poll/view', 'id' => $poll->id, 'tab' => 'members']);
    }


    /**
     * Finds the Member model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Member the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        //if (($model = Member::findOne($id)) !== null) {
        if (($model = Member::find()->primary_key($id)->poll_searchOptions($this->getPollSearchOptions())->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app/error', 'The requested page does not exist.'));
        }
    }

    /*
    public function actionEmail()
    {

        $members = Member::find()->where($this->getPollSearchOptions())->with('codes.vote')->all();

        foreach ($members as $member) {
            \Yii::$app->mailer->compose('sendVotingCodes', ['member' => $member])
                    ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name . ' robot'])
                    ->setTo(ArrayHelper::getColumn($member->contacts, 'email'))
                    ->setSubject('Your voting code ' . \Yii::$app->name)  // todo: multilanguage subject?
                    ->send();
        }
        return true;
    }
    */
}
