<?php

namespace app\controllers;

use Yii;
use app\models\Member;
use app\models\Contact;
use app\models\search\MemberSearch;
use app\components\controllers\BaseController;
use app\components\controllers\PollDependedController;

use app\models\forms\UploadForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use app\components\ExcelParser;

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
                ],
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
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /* Parses an Excel file and creates multiple instances of the Member model.
        * If creation is successful, the browser will be redirected to the
        * 'index' page. */
    public function actionImport()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->excelFile = UploadedFile::getInstance($model, 'excelFile');
            $file = $model->upload();
            if ($file) {
                // Handle errors gracefully.
                set_error_handler(function() {
                    return false;
                });
                $member_dicts = ExcelParser::parseMembers($file->tempName);
                // Restore the default error handler.
                restore_error_handler();
                $errors = [];
                if (!$member_dicts) {
                    $error= 'The file you selected could not be imported';
                    $errors[] = $error;
                } else {
                    $transaction = Yii::$app->db->beginTransaction();
                    foreach ($member_dicts as $dict) {
                        $name = $dict['name'];
                        $member = Member::find()->where(['name' => $name, 'poll_id' => $this->getPollId()])->one();
                        $success = true;
                        // If the member already exists, only re-import the contacts.
                        if($member) {
                            Contact::deleteAll('member_id = :member_id', [':member_id' => $member->id]);
                        } else {
                            $member = new Member();
                            $this->setPollAttributes($member);
                            $member->name = $dict['name'];
                            $member->group = $dict['group'];
                            $success = $member->save();
                        }
                        if($success) {
                            foreach($dict['contacts'] as $contact_dict) {
                                $contact = new Contact();
                                $contact->member_id = $member->id;
                                $contact->name = $contact_dict['name'];
                                $contact->email = filter_var($contact_dict['email'], FILTER_SANITIZE_EMAIL);
                                if(!$contact->save()) {
                                    $row = $contact_dict['row'];
                                    $name = $contact_dict['name'];
                                    $email = $contact_dict['email'];
                                    $error = "Row $row: The contact $name ($email) could not be imported.";
                                    $errors[] = $error;
                                }
                            }
                        } else {
                            $row = $dict['row'];
                            $name = $dict['name'];
                            $error = "Row $row: The member $name could not be imported.";
                            $errors[] = $error;
                        }
                    }
                    $transaction->commit();
                }
                foreach($errors as $error) {
                    Yii::$app->getSession()->addFlash('warning', $error);
                }
                if($member_dicts) {
                    return $this->redirect('index');
                } else {
                    return $this->render('import', ['model' => $model]);
                }
            }
        }
        return $this->render('import', [
            'model' => $model,
        ]);

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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
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
        return $this->redirect(['index']);
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
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionEmail() {

        $members = Member::find()->where($this->getPollSearchOptions())->with('codes.vote')->all();

        foreach($members as $member) {
            \Yii::$app->mailer->compose('sendVotingCodes', ['member' => $member])
                    ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name . ' robot'])
                    ->setTo(ArrayHelper::getColumn($member->contacts, 'email'))
                    ->setSubject('Your voting code ' . \Yii::$app->name)
                    ->send();
        }
        return true;
    }
}
