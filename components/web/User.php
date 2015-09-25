<?php

namespace app\components\web;

/**
 * User adds additional functions to check if current user isAdmin or isOrganizer would return false on guest users
 * application.
 *
 * @author Benjamin Hoft <hoft@eurescom.eu>
 * @since 1.0
 */
class User extends \yii\web\User
{

    public function isAdmin()
    {
        if ($this->identity) {
            return \Yii::$app->user->identity->isAdmin();
        }
        return false;
    }

    public function isOrganizer()
    {
        if ($this->identity) {
            return \Yii::$app->user->identity->isOrganizer();
        }
        return false;
    }

    public function getIsAdmin()
    {
        return $this->isAdmin();
    }

    public function getIsOrganizer()
    {
        return $this->isOrganizer();
    }
}
