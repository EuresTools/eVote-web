<?php


function print_pre($var, $header = null)
{
    if ($header) {
        echo '<strong>'.$header.'</strong>';
    }
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}
