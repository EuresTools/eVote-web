<?php

namespace app\components\behaviors;

use Yii;
use yii\db\ActiveRecord;
use yii\base\Behavior;
use yii\helpers\VarDumper;

class RememberFiltersBehavior extends Behavior
{
    public $limit;

    /**
     * Array that holds any default filter value like array('active'=>'1')
     *
     * @var array
     */
    public $defaults=array();

    /**
     * When this flag is true, the default values will be used also when the user clears the filters
     *
     * @var boolean
     */
    public $defaultStickOnClear=false;

    /**
    * Holds a custom stateId key
    *
    * @var string
    */
    private $_rememberScenario='default';


    //private $debug_as='firebug';
    private $debug_as= __METHOD__;

    public function events()
    {
        return [
            ActiveRecord::EVENT_INIT => 'afterinit',
        ];
    }

    public function afterinit($event)
    {
        // load saved filters
        $this->loadSavedFilters();
    }

    public function getStatePrefix()
    {
        $prefix=\Yii::$app->id.'.'.\Yii::$app->controller->getUniqueId().'/'.\Yii::$app->controller->action->id;
        //\Yii::trace("prefix in getStatePrefix ".\yii\helpers\VarDumper::dumpAsString($prefix), $this->debug_as);
        $modelName = $this->owner->formName();
        //\Yii::trace("modelName in getStatePrefix ".\yii\helpers\VarDumper::dumpAsString($modelName), $this->debug_as);
        if ($this->_rememberScenario!=null) {
            \Yii::trace("getStatePrefix return ".\yii\helpers\VarDumper::dumpAsString($prefix.'-'.$modelName.'-'.$this->_rememberScenario), $this->debug_as);
            return $prefix.'-'.$modelName.'-'.$this->_rememberScenario;
        } else {
            \Yii::trace("getStatePrefix return ".\yii\helpers\VarDumper::dumpAsString($prefix.'-'.$modelName), $this->debug_as);
            return $prefix.'-'.$modelName;
        }
    }

    public function setRememberScenario($value)
    {
        $this->_rememberScenario=$value;
        $this->loadSavedFilters();
        return $this->owner;
    }

    public function getRememberScenario()
    {
        return $this->_rememberScenario;
    }

    private function readSearchValues()
    {
        $modelName = $this->owner->formName();
        $attributes = $this->owner->safeAttributes();
        // set any default value
        if (is_array($this->defaults) && (null==\Yii::$app->session->get($modelName . __CLASS__. 'defaultsSet', null))) {
            foreach ($this->defaults as $attribute => $value) {
                if (null == ($this->getValue($attribute))) {
                    $this->storeValue($attribute, $value);
                }
            }
            \Yii::$app->session->set($modelName . __CLASS__. 'defaultsSet', 1);
        }
        // set values from session
        foreach ($attributes as $attribute) {
            if (null != ($value =  $this->getValue($attribute, null))) {
                try {
                    $this->owner->$attribute = $value;
                    $this->setGetParameters($attribute, $value);
                } catch (Exception $e) {

                }
            }
        }
    }


    private function setGetParameters($attribute, $value)
    {

        $_GET[$this->owner->formName()][$attribute]=$value;
    }

    private function saveSearchValues()
    {
        $attributes = $this->owner->safeAttributes();
        \Yii::trace('saveSearchValues $attributes '. VarDumper::export($attributes), $this->debug_as);

        foreach ($attributes as $attribute) {
            if (isset($this->owner->$attribute)) {
                \Yii::trace('storeValue $attribute '.$attribute.' $value= '.VarDumper::export($this->owner->$attribute), $this->debug_as);
                //print_pre($this->owner->$attribute,'save attribute '. $attribute);
                $this->storeValue($attribute, $this->owner->$attribute);
            } else {
                \Yii::trace('removeValue $attribute '.$attribute, $this->debug_as);
                $this->removeValue($attribute);
            }
        }
    }


    public function loadSavedFilter2s()
    {
        //\Yii::trace('$this->owner '. VarDumper::export($this->owner), 'firebug');
        //\Yii::trace('$this '. VarDumper::export($this), 'firebug');
        //$this->getStatePrefix()

        //\Yii::trace('getStatePrefix '. VarDumper::export($this->getStatePrefix()), 'firebug');
        //\Yii::trace('test 0 '. VarDumper::export($this->getValue('test')), 'firebug');
        $val=$this->getValue('test');

        $this->storeValue('test', ++$val);
        $this->getValue('test');


    }

    public function storeValue($name, $value = null)
    {
        \Yii::trace('storeValue $name '. VarDumper::export($name). ' $value ' .VarDumper::export($value), $this->debug_as);
        return \Yii::$app->session->set($this->getStatePrefix() . $name, $value);
    }

    public function getValue($name, $default = null)
    {
        $val = \Yii::$app->session->get($this->getStatePrefix() . $name);
        if (!$val && $default) {
            return $default;
        }
        \Yii::trace('getValue $name '. VarDumper::export($name). ' $value ' .VarDumper::export($val), $this->debug_as);
        return $val;
    }

    public function removeValue($name)
    {
        \Yii::trace('removeValue $name '. VarDumper::export($name), $this->debug_as);
        return \Yii::$app->session->remove($this->getStatePrefix() . $name);
    }


    public function loadSavedFilters()
    {
        \Yii::trace('$this->owner->getScenario()'. VarDumper::export($this->owner->getScenario()), $this->debug_as);
        if ($this->owner->scenario == $this->rememberScenario) {
            \Yii::trace('scenario == rememberScenario so loadSavedFilters', $this->debug_as);

            // store the sorting order in the session
            $key = get_class($this->owner).'_sort';
            if (!empty($_GET[$key])) {
                $this->storeValue('sort', $_GET[$key]);
            } else {
                $val = $this->getValue('sort');
                if (!empty($val)) {
                    $_GET[$key] = $val;
                }
            }

            // store the active page in page in the session
            $key = get_class($this->owner).'_page'; // get parameter "Classname"_page
            if (!empty($_GET[$key])) {
                $this->storeValue('page', $_GET[$key]);
            } elseif (!empty($_GET["ajax"])) {
                // page 1 passes no page number, just an ajax flag
                $this->storeValue('page', 1);
            } else {
                $val = $this->getValue('page');
                if (!empty($val)) {
                    $_GET[$key] = $val;
                }
            }

            // store the "limit in the session"
            $key = get_class($this->owner).'_limit'; // get parameter "Classname"_limit

            if (!empty($_GET[$key])) {
                $this->storeValue('limit', (int) $_GET[$key]);
            } else {
                $val = $this->getValue('limit');
                if (!empty($val)) {
                    $_GET[$key] = $val;
                }
            }

            if (property_exists($this->owner, 'limit')) {
                // set default limit to the owner
                //$this->owner->limit=Yii::app()->user->getState($this->getStatePrefix() . 'limit', Yii::app()->params['defaultPageSize']);
            }

            // set submitted values and save them if nothing submitted use the saved values.
            /*
            \Yii::trace('_GET in RememberFiltersBehavior  '. VarDumper::export($_GET), 'firebug');
            if (isset($_GET[$this->owner->formName()])) {
                \Yii::trace('owner formName '. VarDumper::export($this->owner->formName()), 'firebug');
                \Yii::trace('_GET formName  '. VarDumper::export($_GET[$this->owner->formName()]), 'firebug');
            }
            */


            // only save new search values if load + validate == true otherwise false search values could be saved in the session ??
            // load needs the complete get request
            if ($this->owner->load(Yii::$app->request->getQueryParams()) && $this->owner->validate()) {
                \Yii::trace('load + validate successfull saveSearchValues', $this->debug_as);
                $this->saveSearchValues();
            } else {
                \Yii::trace('load + validate NOT successfull readSearchValues ', $this->debug_as);
                $this->readSearchValues();
            }
            //\Yii::trace('owner getAttributes  '. VarDumper::export($this->owner->getAttributes()), 'firebug');
            /*
            if (Yii::$app->request->get($this->owner->formName())) {
                //if (isset($_GET[$this->owner->formName()])) {
                //if (isset(Yii::$app->request->get($this->owner->formName()))) {
                // set the get attributes in the owner
                //$this->owner->load(Yii::$app->request->get());
                $this->owner->load(Yii::$app->request->get($this->owner->formName()));
                $this->saveSearchValues();
            } else {
                $this->readSearchValues();
            }
            */
        }
    }
}
