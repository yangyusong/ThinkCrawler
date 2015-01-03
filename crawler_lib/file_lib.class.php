<?php
/**
 * file api
 * @date 15-1-1
 * @author: YuSongYang
 * @mail yys159258@126.com
 */

class file_lib{
    static function wt($path,$content){
//        echo $path;
//        chmod($path, 0777);
        $fp = fopen($path, "w");
        fwrite($fp, $content);
        fclose($fp);
    }

    static function rd($template){
//        chmod($template, 0777);
        $fp = fopen($template, "r");
        $content = fread($fp, filesize($template));
        fclose($fp);
        return $content;
    }
}

?>
