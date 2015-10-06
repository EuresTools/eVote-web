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
            $this->defineAttribute('vote_submitted', $value = 1);
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
        $this->defineAttribute('vote_submitted', $value = 1);
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

        // if ($this->_min_options >= 1) {
        //     $this->addRule(['options'], 'required', ['message'=>'{attribute} must be selected']);
        // }

        $this->addRule(['vote_submitted'], 'safe');
        $this->addRule(['vote_submitted'], 'required');

        $this->addRule(['options'], 'safe');  // enables submittion of the attribute
        $this->addRule(['options'], 'validateOptions', ['skipOnEmpty'=>false, 'skipOnError'=>false]);               // does the validation
        $this->addRule(['options'], 'each', ['rule' => ['integer']]); // checks if every options ID is an integer
    }

    public function validateOptions($attribute, $params)
    {
        // check for maximum selection
        if (isset($this->_max_options)) {
            if (sizeof($this->$attribute) > $this->_max_options) {
                //$this->addError($attribute, Yii::t('app', 'Please select a maximum of {count} options', ['count' => $this->_max_options]));
                $this->addError($attribute, Yii::t('app', 'Please select maximum {count, plural, =0{# Option} =1{# Option} other{# Options}}', ['count' => $this->_max_options]));
            }
        }

        // check for minumum selection
        if (isset($this->_min_options)) {
            if (sizeof($this->$attribute) < $this->_min_options) {
                //$this->addError($attribute, Yii::t('app', 'Please select at least {count} options', ['count' => $this->_min_options]));
                $this->addError($attribute, Yii::t('app', 'Please select at least {count, plural, =0{# Option} =1{# Option} other{# Options}}', ['count' => $this->_min_options]));
            }
        }

        // check that only available options are selected
        if (sizeof($this->$attribute)) {
            if (is_array($this->$attribute)) {
            // for multiple selection e.g. checkbox list all entries must available in the form options array
                if (array_diff($this->$attribute, array_keys($this->getFormOptions()))) {
                    $this->addError($attribute, 'Please select only from the available options.');
                }
            } else {
                // for single selection e.g. radio
                if (!in_array($this->$attribute, array_keys($this->getFormOptions()))) {
                    $this->addError($attribute, 'Please select only from the available options.');
                }
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



    public function getOptionsCountText()
    {
        $selected_options =  $this->options ? sizeof($this->options) : 0;
        $counter_text= yii\helpers\Html::tag('span', $selected_options, ['class'=>'options-counter']);
        $html='<strong>Note:</strong> ';
        if ($this->_min_options && $this->_max_options) {
            $html.= Yii::t('app', 'Select minimum {minimum}, maximum {maximum} Options. Options selected: {count}.', ['minimum'=>$this->_min_options, 'maximum'=>$this->_max_options, 'count'=>$counter_text]);
        } elseif ($this->_min_options) {
            $html.= Yii::t('app', 'Select minimum {minimum, plural, =0{# Option} =1{# Option} other{# Options}}. Options selected: {count}.', ['minimum'=>$this->_min_options, 'count'=>$counter_text]);
        } elseif ($this->_max_options) {
            $html.= Yii::t('app', 'Select maximum {maximum, plural, =0{# Option} =1{# Option} other{# Options}}. Options selected: {count}.', ['maximum'=>$this->_max_options, 'count'=>$counter_text]);
        } else {
            $html.= Yii::t('app', 'Options selected: {count}.', ['count'=>$counter_text]);
        }
        return $html;
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
                            'options'=>['unselect'=>null],
                            //'options'=>['inline'=>true]
                        ];
                    break;
                // case 'vote_submitted':
                //     $fields[$attribute] = $fields[$attribute] = [
                //             'type'=>Form::INPUT_HIDDEN,
                //             'options'=>['label'=>false],
                //     ];
                //     break;
                case 'options2':
                        $fields[$attribute] = [
                            'type'=>Form::INPUT_RADIO_LIST,
                            'items'=>$this->getFormOptions(),
                            'options'=>['inline'=>true],
                        ];
                    break;
            }
        }
        return $fields;
    }
}
