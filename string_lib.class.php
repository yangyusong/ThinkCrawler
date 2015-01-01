<?php
/**
 * string api
 * @date 2011-05-12
 * @author: YuSongYang
 * @mail yys159258@126.com
 */

class string_lib
{

    static function start_with($str, $ch)
    {
        return substr($str, 0, strlen($ch)) == $ch;
    }

    static function end_with($str, $ch)
    {
        return substr($str, strlen($str) - strlen($ch), strlen($ch)) == $ch;
    }

    //file drop suffix
    static function no_suffix($filename)
    {
        $filename = explode(".", $filename);
        array_pop($filename);
        return implode(".", $filename);
    }

    static function str_str($str)
    {
        return "'" . $str . "'";
    }
}

?>
