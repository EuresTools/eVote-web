<?php

namespace app\components\console;

use yii\console\Exception;

/**
 * User adds additional functions to check if current user isAdmin or isOrganizer would return false on guest users
 * application.
 *
 * @author Benjamin Hoft <hoft@eurescom.eu>
 * @since 1.0
 */
class User extends \yii\web\User
{
    public $enableAutoLogin = false;

    public function init()
    {
        parent::init();

        $identity = \app\models\User::findByUsername('console_user');
        if ($identity) {
            $this->setIdentity($identity);
        } else {
            throw new Exception("Error console_user not found cannot set console user identity", 1);
        }
    }

    public function isAdmin()
    {
        return true;
    }

    public function isOrganizer()
    {
        return true;
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
