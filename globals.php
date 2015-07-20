<?php


function print_pre($var, $header = null, $debug = true, $return = false, $depth = 3, $highlight = null)
{
    if ($header) {
        echo '<strong>'.$header.'</strong>';
    }
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}


function print_model(&$model_or_array, $description = "", $debug = true, $return = false)
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
