<?php

namespace app\models\forms;

use Yii;
use yii\base\DynamicModel;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use app\models\Poll;
use yii\web\NotFoundHttpException;

/**
 * VotingForm is the model behind the voting form.
 */
class VotingForm extends DynamicModel
{
    private $_poll;
    private $_code;
    private $_options;
    private $_min_options = null;
    private $_max_options = null;

    public $isNewRecord = true;

    public $question;
    public $header;

    //poll_id is used for the preview mode without using a token
    public function __construct(&$code, $poll_id = null)
    {
        // here i must get the valid attributes which can be set through the poll questions
        // and define the attributes and maybe set the default values.
        if ($code) {
            $this->_code=$code;
            if (!$this->getPoll()) {
                throw new Exception("Poll selection failed", 1);
            }
            if (!$this->getOptions()) {
                throw new Exception("Option selection failed", 1);
            }

            $this->header=$this->_poll->title;
            $this->question=$this->_poll->question;
            // set the min and max options to be selected
            $this->_min_options= $this->_poll->select_min;
            $this->_max_options= $this->_poll->select_max;

            $this->defineAttribute('options', $value = null);
            $this->setOptionRules();
        } elseif (isset($poll_id)) {
            $this->setPreviewMode($poll_id);
        }

        $attributes = [];
        $config = [];
        parent::__construct($attributes, $config);
    }

    protected function setPreviewMode($id)
    {
        $this->_poll=$this->getPollByID($id);
        if (!$this->getOptions()) {
                throw new Exception("Option selection failed", 1);
        }
        $this->header=$this->_poll->title;
        $this->question=$this->_poll->question;
        // set the min and max options to be selected
        $this->_min_options= $this->_poll->select_min;
        $this->_max_options= $this->_poll->select_max;

        $this->defineAttribute('options', $value = null);
        $this->setOptionRules();
        return true;
    }

    protected function getPollByID($id)
    {
        if (($model = Poll::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app/error', 'The requested page does not exist.'));
        }
    }

    protected function setOptionRules()
    {
        if ($this->_min_options >= 1) {
            $this->addRule(['options'], 'required', ['message'=>'{attribute} must be selected']);
        }
        $this->addRule(['options'], 'safe');                          // enables submittion of the attribute
        $this->addRule(['options'], 'validateOptions');               // does the validation
        $this->addRule(['options'], 'each', ['rule' => ['integer']]); // checks if every options ID is an integer
    }

    public function validateOptions($attribute, $params)
    {
        // check for maximum selection
        if (isset($this->_max_options)) {
            if (sizeof($this->$attribute) > $this->_max_options) {
                $this->addError($attribute, Yii::t('app', 'Please select a maximum of {count} options', ['count' => $this->_max_options]));
            }
        }

        // check for minumum selection
        if (isset($this->_min_options)) {
            if (sizeof($this->$attribute) < $this->_min_options) {
                $this->addError($attribute, Yii::t('app', 'Please select at least {count} options', ['count' => $this->_min_options]));
            }
        }

        // check that only available options are selected
        if (is_array($this->$attribute)) {
            // for multiple selection e.g. checkbox list all entries must available in the form options array
            if (array_diff($this->$attribute, array_keys($this->getFormOptions()))) {
                $this->addError($attribute, 'Please selection only from the available options.');
            }
        } else {
            // for single selection e.g. radio
            if (!in_array($this->$attribute, array_keys($this->getFormOptions()))) {
                $this->addError($attribute, 'Please selection only from the available options.');
            }
        }
    }


    public function getPoll()
    {
        if ($this->_code) {
            $this->_poll=$this->_code->getPoll()->one();
            return $this->_poll;
        }
        return false;
    }

    protected function getOptions()
    {
        if ($this->_poll) {
            $this->_options=$this->_poll->getOptions()->all();
            return $this->_options;
        }
        return false;
    }

    public function getOptionById($id)
    {
        if ($this->_poll) {
            return $this->_poll->getOptions()->where(['id' => $id])->one();
        }
        return false;

    }

    public function getFormOptions()
    {
        if (isset($this->_options)) {
            return ArrayHelper::map($this->_options, 'id', 'text');
        }
        return false;
    }


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
        ];
    }


    public function getFormFields()
    {
        // return the config files for dynamic form fields
        $fields = [];
        foreach ($this->attributes as $attribute => $value) {
            switch ($attribute) {
                case 'options':
                    $fields[$attribute] = $fields[$attribute] = [
                            'type'=>Form::INPUT_CHECKBOX_LIST,
                            'items'=>$this->getFormOptions(),
                            //'options'=>['inline'=>true]
                        ];
                    break;
                case 'options2':
                        $fields[$attribute] = [
                            'type'=>Form::INPUT_RADIO_LIST,
                            'items'=>$this->getFormOptions(),
                            'options'=>['inline'=>true]
                        ];
                    break;
            }
        }
        return $fields;
    }
}
