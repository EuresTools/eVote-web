<?php

namespace app\models\forms;

use Yii;
use app\models\User;

class UserForm extends User
{
    public $new_password;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['new_password'], 'safe'],
            [['new_password'], 'string', 'max' => 255],
        ]);
    }

    public function beforeSave($insert)
    {
        if (!empty($this->new_password)) {
            $this->setNewPassword($this->new_password);
        }
        if ($this->isNewRecord) {
            $this->auth_key = \Yii::$app->security->generateRandomString();
        }
        return parent::beforeSave($insert);
    }
}
