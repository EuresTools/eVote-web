<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\Code;

class TokenInputForm extends Model
{
    public $token;
    private $_code = false;

    public function rules()
    {
        return [
            [['token'], 'required'],
            // token is validated by validateToken()
            ['token', 'validateToken'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'token' => Yii::t('app', 'Token'),
        ];
    }

    public function validateToken($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $code = $this->getCode();

            if (!$code) {
                // no code with token found
                $this->addError($attribute, 'Incorrect token or token already used.');
            } else {
                // code with token found now check if is was already used or disabled.
                if ($code->isUsed()) {
                    $this->addError($attribute, 'Token was already used to vote.');
                }
                if (!$code->isValid()) {
                    $this->addError($attribute, 'Incorrect token or token already used.');
                }
            }

        }
    }

    public function getCode()
    {
        if ($this->_code === false) {
            $this->_code = Code::findCodeByToken($this->token);
        }
        return $this->_code;
    }
}
