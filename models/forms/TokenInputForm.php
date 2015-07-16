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

    public function validateToken($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $code = $this->getCode();

            if (!$code) {
                $this->addError($attribute, 'Incorrect token or token already used.');
            }
            if ($code->isUsed()) {
                $this->addError($attribute, 'Token was already used to vote.');
            }
            if (!$code->isValid()) {
                $this->addError($attribute, 'Incorrect token or token already used.');
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
