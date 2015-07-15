<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * LoginForm is the model behind the login form.
 */
class EmailForm extends Model
{
    const EMAIL_TO_ALL = 0;
    const EMAIL_TO_UNUSED = 1;
    const EMAIL_TO_USED = 2;


    public $subject;
    public $message;
    public $poll;
    public $sendMode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['sendMode', 'subject', 'message'], 'required'],
        ];
    }

    public function attributeLabels() {
        return [
            'sendMode' => 'To',
        ];
    }
}
