<?php
/**
 * @author YangYuSong
 * Date: 15-1-1
 * Time: 下午11:12
 */

class url{
    /**
     * enum types
     * javascrip:
     * #
     * .css
     * @param $urls
     */
    static function dropBadUrls($urls){
        $notSoBad = array();
        foreach ($urls as $url) {
            if(!preg_match('/^javascript/', trim($url))
                && !preg_match('/^#/', trim($url))
                && !preg_match('/^mailto/', trim($url))
                && !preg_match('/^\/$/', trim($url))
                && !'' == trim($url)
                && !preg_match('/.css$/', trim($url)))
            {
                $notSoBad[] = $url;
            }
        }
        return $notSoBad;

    }

    static function toAbsolutePath($urls, $crawlerPath){
        $dirPath = static::dirUrl($crawlerPath);
        $absolutePaths = array();
        foreach ($urls as $url) {
            if(preg_match('/^\//', $url))
            {
                $absolutePaths[] = $dirPath. $url;
            }
            else{
                $absolutePaths[] = $url;
            }
        }

        return $absolutePaths;
    }

    static function dirUrl($url){
        $pathArr = explode('/', $url);
        array_pop($pathArr);
        return implode('/', $pathArr);
    }


}
