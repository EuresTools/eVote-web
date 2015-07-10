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
            [['username'], 'required'],
            [['new_password'], 'required', 'on' => ['create']],
            [['new_password'], 'safe'],
            [['new_password'], 'string', 'max' => 255],
        ]);
    }

    public function scenarios() {
        return [
            'default' => ['username', 'new_password', 'is_admin', 'organizer_id'],
            'create' => ['username', 'new_password', 'is_admin', 'organizer_id'],
            'update' => ['username', 'new_password', 'is_admin', 'organizer_id'],
        ];
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
