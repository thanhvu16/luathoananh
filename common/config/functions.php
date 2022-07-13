<?php
/**
 * Created by PhpStorm.
 * User: ungnv
 * Date: 4/20/2017
 * Time: 3:54 PM
 */

/**
 * Debug function
 * d($var);
 */
function d($var,$caller=null)
{
    if(!isset($caller)){
        $caller = array_shift(debug_backtrace(1));
    }
    echo '<code>File: '.$caller['file'].' / Line: '.$caller['line'].'</code>';
    echo '<pre>';
    yii\helpers\VarDumper::dump($var, 10, true);
    echo '</pre>';
}

/**
 * Debug function with die() after
 * dd($var);
 */
function dd($var)
{
    $caller = array_shift(debug_backtrace(1));
    d($var,$caller);
    die();
}

/**
 * Convert mobile 0xxx => 84xxx
 *
 * @param $rawMobile
 * @return mixed
 */
function standardizeMobile($rawMobile) {
    $mobile = preg_replace('/^0/', '84', $rawMobile);
    return $mobile;
}

/**
 * prettyMobile
 * Convert mobile 84xxx => 0xxx
 *
 * @param string $rawMobile
 * @return string
 */
function prettyMobile($rawMobile) {

    $mobile = preg_replace('/^84/', '0', $rawMobile);
    return $mobile;
}
