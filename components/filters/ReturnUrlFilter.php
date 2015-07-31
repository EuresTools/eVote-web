<?php

namespace app\components\filters;

use Yii;
use Yii\base\ActionFilter;
use yii\helpers\Json;

class ReturnUrlFilter extends ActionFilter
{
    public $only =  [
        'update',
        'create',
        'delete'
    ];

    public function beforeAction($action)
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            // only works for non ajax Requests
            $url_referer=Yii::$app->request->getReferrer();
            $url_request=$this->getRequestUrl();

            if ($url_referer && $url_referer!=$url_request) {
                $action->controller->setReturnUrl($url_referer);
            }

            if ($request->getIsPost() && $request->post('returnUrl')) {
                $action->controller->setReturnUrl(Json::decode($_POST['returnUrl']));
            }
        }
        return true;
    }


    protected function getRequestUrl()
    {
        $url=Yii::$app->request->getHostInfo().Yii::$app->request->getUrl();
        return $url;
    }
}
