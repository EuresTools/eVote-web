<?php

namespace app\components\filters;

class OrganizationAccessRule extends \yii\filters\AccessRule
{
    public $allow = true;  // Allow access if this rule matches
    public $roles = ['@']; // Ensure user is logged in.

    public $allowAdminAllAccess = true;
    public $modelClass;
    public $queryParam = 'id';
    public $queryMethod = 'get';

    public function allows($action, $user, $request)
    {
        $parentRes = parent::allows($action, $user, $request);
        // $parentRes can be `null`, `false` or `true`.
        // True means the parent rule matched and allows access.
        if ($parentRes !== true) {
            return $parentRes;
        }

        // admins are allowed to edit entries from other organizations
        if ($user->identity->isAdmin() && $this->allowAdminAllAccess) {
            return true;
        }

        return ($this->getOrganizationId($action, $request) == $user->identity->organizer_id);
    }

    private function getOrganizationId($action, $request)
    {
        if ($this->queryMethod === 'get') {
            $id = $request->get($this->queryParam);
        } elseif ($this->queryMethod === 'post') {
            $id = $request->post($this->queryParam);
        }


        if ($this->modelClass) {
            $modelClass=$this->modelClass;
        } else {
            $modelClass='\app\models\Poll';
        }
        $model = $modelClass::findOne($id);

        // Fill in code to receive the right poll.
        // assuming the poll id is given à la `poll/update?id=1`
        if ($model) {
            if (is_callable(array($model,'getOrganizerId'))) {
                return $model->getOrganizerId();
            }
        }
        return null;
    }
}
