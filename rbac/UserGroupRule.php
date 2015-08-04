<?php
namespace app\rbac;

use Yii;
use yii\rbac\Rule;

class UserGroupRule extends Rule
{
    public $name = 'userGroup';

    public function execute($user, $item, $params)
    {
        if (!\Yii:: $app ->user->isGuest) {
            if ($item->name === 'Admin') {
                return \Yii::$app->user->identity->isAdmin();
            } elseif ($item->name === 'Organizer') {
                // all users have the Organizer Role
                return true;
            }
        }
        return false;
    }

    /*
    normal behavior is to check the user accounts "group" attribute

    if (isset(\Yii::$app->user->identity->Group)) {
        $group = \Yii::$app->user->identity->Group;
    } else {
        $group = null;
    }

    if ($item->name === 'Admin') {
        return $group == 'Admin';
    } elseif ($item->name === 'Organizer') {
        return $group == 'Admin' || $group == 'Organizer';
    }
    //elseif ($item->name === 'SomeOtherRole') {
    //  return $group == 'Admin' || $group == 'SomeOtherRole' ;
    //}

    */
}
