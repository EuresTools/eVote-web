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

    const SCENARIO_MULTIPLE_EMAIL = 'multiEmail';

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
            [['subject', 'message'], 'required'],
            [['sendMode'], 'required',  'on' => self::SCENARIO_MULTIPLE_EMAIL],
        ];
    }

    public function attributeLabels() {
        return [
            'sendMode' => 'To',
        ];
    }
}
