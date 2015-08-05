<?php
namespace app\rbac;

use Yii;
use yii\rbac\Rule;

class OrganizerRule extends Rule
{
    public $name = 'organizerCheck';

    public function execute($user, $item, $params)
    {
        if (!\Yii:: $app ->user->isGuest) {
            if (\Yii::$app->user->identity->isAdmin()) {
                return true;
            } else {
                return \Yii::$app->user->identity->organizer_id;
            }
        }
        return false;
    }
}
