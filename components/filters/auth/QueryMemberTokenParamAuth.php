<?php

namespace app\components\filters\auth;

use yii\filters\auth\AuthMethod;
use app\models\Code;
use app\models\User;

/**
 * QueryParamAuth is an action filter that supports the authentication based on the access token passed through a query parameter.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class QueryMemberTokenParamAuth extends AuthMethod
{
    /**
     * @var string the parameter name for passing the access token
     */
    public $tokenParam = 'token';

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $accessToken = $request->get($this->tokenParam);
        if (is_string($accessToken)) {
            $code = Code::findCodeByToken($accessToken, get_class($this));
            // check is is used maybe?
            if ($code !== null) {
                //return new User();
                return true;
            }
        }
        if ($accessToken !== null) {
            $this->handleFailure($response);
        }

        return null;
    }
}
