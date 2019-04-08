<?php
/**
 * Author: ppshobi@gmail.com
 *
 */

if(! function_exists('quote')) {
    /**
     * append and prepend a string with double quotes
     * @param string $string
     * @return string
     */
    function quote(string $string)
    {
        return "\"" . $string . "\"";
    }
}