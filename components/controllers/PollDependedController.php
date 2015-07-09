<?php

namespace app\components\controllers;

use Yii;
use app\components\controllers\BaseController;


use app\models\Poll;
use app\models\search\PollSearch;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;
use app\components\helpers\PollUrl;

class PollDependedController extends BaseController
{
    private $_poll_id;
    private $_poll_model;

    public $debug_as= __METHOD__;
    //public $debug_as= 'firebug';

    public function beforeAction($action)
    {
        \Yii::trace("this->id in BaseProjectController beforeAction".\yii\helpers\VarDumper::dumpAsString($this->id), $this->debug_as);
        if (parent::beforeAction($action)) {
            \Yii::trace("_GET in BaseProjectController beforeAction".\yii\helpers\VarDumper::dumpAsString($_GET), $this->debug_as);
            $poll_id = \Yii::$app->request->get('poll_id');
            if ($poll_id && $this->findPoll($poll_id)) {
                return true;  // or false if needed
            } else {
                // todo: forward to poll select page or error page you are not it the given poll
                //return false;
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findPoll($poll_id)
    {
        $model = Poll::find()->where(['id' => $poll_id])->one();
        if ($model !== null) {
            // Todo: test if the current user has access to this model
            $this->_poll_model=$model;
            $this->_poll_id=$model->getPrimaryKey();
            return $model;
        } else {
            $this->_poll_model=null;
            $this->_poll_id=null;
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getPollSearchOptions()
    {
        return ['poll_id' => $this->_poll_id];
    }

    protected function setPollSearchOptions(&$searchModel)
    {
        if ($this->_poll_id) {
            $searchModel->setAttribute('poll_id', $this->_poll_id);
        } else {
            throw new InvalidParamException('"' . get_called_class() . '" poll_id isn\'t set as as parameter.');
        }
    }

    protected function setPollAttributes(&$model)
    {
        if ($this->_poll_id) {
            $model->setAttribute('poll_id', $this->_poll_id);
        } else {
            throw new InvalidParamException('"' . get_called_class() . '" poll_id couldn\'t be set as an attribute.');
        }
    }

    public function getPollId()
    {
        return $this->_poll_id;
    }

    /*
    public function getPollAlias()
    {
        return $this->_poll_model->getAttribute('Poll::SLUG_CONST');
    }
    */

    public function getPollDisplay()
    {
        return $this->_poll_model->__toString();
    }

    public function redirect($url, $statusCode = 302)
    {
        // test if url still redirects to this controller
        if (strncmp($url[0], '/', 1) !== 0) {
            return Yii::$app->getResponse()->redirect(PollUrl::to($url), $statusCode);
        } else {
            return parent::redirect($url, $statusCode);
        }
    }

    public function createUrl($url = '', $scheme = false)
    {
        return PollUrl::toRoute($url, $scheme);
    }
}
