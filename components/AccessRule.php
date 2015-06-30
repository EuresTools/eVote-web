<?php

namespace app\components;

use app\models\User;

class AccessRule extends \yii\filters\AccessRule {

    protected function matchRole($user) {
        if (empty($this->roles)) {
            return true;
        }

        foreach ($this->roles as $role) {
            if ($role === '?') {
                return $user->getIsGuest();
            }
            elseif ($role === '@') {
                return !$user->getIsGuest();
            }
            elseif ($role === 'admin') {
                return !$user->getIsGuest() && $user->identity->isAdmin();
            }
            elseif ($role === 'organizer') {
                return !$user->getIsGuest() && $user->identity->isOrganizer();
            }
            return false;
        }
    }
}
