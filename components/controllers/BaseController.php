<?php

namespace app\components\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{

    public function setReturnUrl($returnUrl = null, $action = null)
    {
        if (!$action) {
            $action = $this->action;
        }

        $id = $action->getUniqueId();

        if ($returnUrl) {
            Yii::$app->session->set($id.'_returnUrl', $returnUrl);
        } else {
            Yii::$app->session->remove($id.'_returnUrl');
        }
    }


    public function getReturnUrl($default_action = null, $delete = true)
    {
        $id = $this->action->getUniqueId();
        $returnUrl = Yii::$app->session->get($id.'_returnUrl');

        if ($delete) {
            $this->setReturnUrl(null);
        }

        if ($returnUrl) {
            return $returnUrl;
        }

        if ($default_action) {
            return $default_action;
        }
        return [$this->defaultAction];
    }
}
