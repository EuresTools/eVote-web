<?php

namespace app\models;

use Yii;
use \app\models\query\CodeQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\base\ModelEvent;

class Code extends \app\models\base\CodeBase
{
    const CODE_STATUS_INVALID_USED = -2;
    const CODE_STATUS_INVALID_UNUSED = -1;
    const CODE_STATUS_UNUSED = 1;
    const CODE_STATUS_USED = 2;

    const EVENT_SEND_TOKEN = 'sendToken';

    public function init()
    {
        parent::init();
        $this->on(self::EVENT_SEND_TOKEN, [$this, 'sendToken']);
    }

    /**
     * @return returns representingColumn default null
     */
    public static function representingColumn()
    {
        if (\Yii::$app->user->isAdmin()) {
            return ['token'];
        }
        return ['ScrambleToken'];
    }

    /**
     * @inheritdoc
     * @return CodeQuery
     */
    public static function find()
    {
        return new CodeQuery(get_called_class());
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    self::EVENT_SEND_TOKEN => ['sent_at'],
                ],
                'value' => new Expression('UTC_TIMESTAMP()'),
            ],
        ]);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
        ]);
    }

    public static function generateCode($poll_id, $member_id)
    {
        $code = new Code();
        $code->member_id = $member_id;
        $code->poll_id = $poll_id;
        $length = 10;
        $code->token = Yii::$app->getSecurity()->generateRandomString($length);
        // Better safe than sorry, avoid collisions.
        while (!$code->validate(['token'])) {
            $code->token = Yii::$app->getSecurity()->generateRandomString($length);
        }
        return $code;
    }

    public function isValid()
    {
        return in_array($this->code_status, [self::CODE_STATUS_UNUSED, self::CODE_STATUS_USED]);
    }

    public function isUsed()
    {
        return in_array($this->code_status, [self::CODE_STATUS_USED, self::CODE_STATUS_INVALID_USED]);
    }

    public function isNotUsed()
    {
        return in_array($this->code_status, [self::CODE_STATUS_UNUSED, self::CODE_STATUS_INVALID_UNUSED]);
    }

    public function isInValid()
    {
        return in_array($this->code_status, [self::CODE_STATUS_INVALID_USED, self::CODE_STATUS_INVALID_UNUSED]);
    }


    public static function findCodeByToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    public function getHTMLOptions()
    {
        if (!$this->isValid()) {
            return self::getInvalidHTMLOptions();
        }
        if (!$this->isUsed()) {
            return self::getUnusedHTMLOptions();
        }
        if ($this->isUsed()) {
            return self::getUsedHTMLOptions();
        }
    }

    public static function getInvalidHTMLOptions()
    {
        return ['class' => 'token-invalid', 'title' => Yii::t('app', 'This voting code has been invalidated')];
    }

    public static function getUnusedHTMLOptions()
    {
        return ['class' => 'token-valid', 'title' => Yii::t('app', 'This voting code has not been used')];
    }

    public static function getUsedHTMLOptions()
    {
        return ['class' => 'token-used', 'title' => Yii::t('app', 'A vote has been submitted using this voting code')];
    }


    /*
    check code if code can be used for submission
    returns false for already used and disabled codes.
    sets the error message on the attribute token
     */
    public function checkCode()
    {
        if ($this->isInValid()) {
            $this->addError('token', Yii::t('app/error', 'Invalid voting code'));
            return false;
        }
        if ($this->isUsed()) {
            $this->addError('token', Yii::t('app/error', 'This voting code has already been used'));
            return false;
        }
        return true;
    }


    public function invalidate()
    {
        if ($this->isValid()) {
            if ($this->isUsed()) {
                $this->code_status = self::CODE_STATUS_INVALID_USED;
            } else {
                $this->code_status = self::CODE_STATUS_INVALID_UNUSED;
            }
        }
    }

    public function getOrganizerId()
    {
        $poll = $this->getPoll()->one();
        return isset($poll) ? $poll->organizer_id : null;
    }

    // return just first and last token char rest scrabled
    public function getScrambleToken()
    {
        if (isset(Yii::$app->params['readable-token-chars'])) {
            $readableChars = (int) Yii::$app->params['readable-token-chars'];
        } else {
            $readableChars = 0;
        }
        $start = $readableChars;
        $maxlength = strlen($this->token) - ($readableChars * 2);
        $replace='';
        if ($start <= $maxlength) {
            $replace = implode('', array_fill($start, $maxlength, '*'));
        }
        return substr_replace($this->token, $replace, $start, $maxlength);
    }

    public function sendToken($event)
    {
        $this->touch('sent_at');
        return $event->isValid;
    }

    public function getTokenForEmail()
    {
        $event = new ModelEvent;
        $this->trigger(self::EVENT_SEND_TOKEN, $event);
        return $this->getAttribute('token');
    }
}
