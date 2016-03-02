<?php

function print_pre($var, $header = null, $debug = true, $return = false, $depth = 3, $highlight = null)
{
    if (!isset($highlight)) {
            $highlight=false;
    }
    if ($debug && YII_DEBUG) {
        \Yii::trace($header.' '.yii\helpers\VarDumper::dumpAsString($var, $depth, $highlight), 'firebug');
    } else {
        $html = '';
        if ($header) {
            $html = "<strong>".$header."</strong>\n";
        }
        $html .= "\n<pre class='debug'>";
        //$html .= print_r($var, true);
        $html .= yii\helpers\VarDumper::dumpAsString($var, $depth, $highlight);
        $html .= "</pre>\n";
        if ($return) {
            return $html;
        }
        echo $html;
    }
}

function print_model($model_or_array, $description = "", $debug = true, $return = false)
{
    if (is_object($model_or_array)) {
        return print_pre($model_or_array->getAttributes(), '(Model) '.$description, $debug, $return);
    } else {
        $array = array();
        if (is_array($model_or_array)) {
            foreach ($model_or_array as $model) {
                $array[] = $model->getAttributes();
            }
        } else {
            $array=$model_or_array;
        }
        return print_pre($array, '(Model[s]) '.$description, $debug, $return);
    }
}

// returns true if "ALL" needles are present in the haystack
// good for checking if all options are given in the available options list
function in_array_all($needles, $haystack)
{
    return !array_diff($needles, $haystack);
}


// returns true if ANY of the needles exist in the haystack
// so at least one needle must be given in the haystack
function in_array_any($needles, $haystack)
{
    return !!array_intersect($needles, $haystack);
}
