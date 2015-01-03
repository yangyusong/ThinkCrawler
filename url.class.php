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
                && !preg_match('/^\/#/', trim($url))
                && !preg_match('/\.jpg$/', trim($url))
                && !'' == trim($url)
                && !preg_match('/\.css$/', trim($url)))
            {
                $notSoBad[] = $url;
            }
        }
        return $notSoBad;

    }

    static function delMulti($urls){
        $urlKvs = array();
        foreach ($urls as $url) {
            $urlKvs[$url] = $url;
        }

        return array_keys($urlKvs);
    }

    static function toAbsPath($urls, $crawlerPath){
//        require_once('crawler_lib/domain.class.php');
        $dirPath = static::dirUrl($crawlerPath);
        $absolutePaths = array();
        foreach ($urls as $url) {
            if(preg_match('/^\//', $url))
            {
                $absolutePaths[] = $dirPath. $url;
            }
            else{
                if(trim(domain::getDomain($url)).'' == '')
                {
                    $absolutePaths[] = $dirPath. '/'. $url;
                }
                else{
                    echo domain::getDomain($url)."\n";
                    $absolutePaths[] = $url;
                }
            }
        }

        return $absolutePaths;
    }

//    static function toAbsolutePath($urls, $crawlerPath){
//        $dirPath = static::dirUrl($crawlerPath);
//        $absolutePaths = array();
//        foreach ($urls as $url) {
//            if(preg_match('/^\//', $url))
//            {
//                $absolutePaths[] = $dirPath. $url;
//            }
//            else{
//                require_once('crawler_lib/domain.class.php');
//                if(trim(domain::getDomain($url)) != '')
//                {
//                    $absolutePaths[] = $url;
//                }
//            }
//        }
//
////        print_r($absolutePaths);
//
//        return $absolutePaths;
//    }

    static function dirUrl($url){
        $urlArray = static::jParseUrl($url);
        if(trim($urlArray['path']) != '')
        {
            $pathArr = explode('/', $url);
            array_pop($pathArr);
            return implode('/', $pathArr);
        }

        if(preg_match('/\/$/', $url))
        {
            return $url;
        }

        return $url. '/';

    }

    static function jParseUrl($url) {
        $r  = "(?:([a-z0-9+-._]+)://)?";
        $r .= "(?:";
        $r .=   "(?:((?:[a-z0-9-._~!$&'()*+,;=:]|%[0-9a-f]{2})*)@)?";
        $r .=   "(?:\[((?:[a-z0-9:])*)\])?";
        $r .=   "((?:[a-z0-9-._~!$&'()*+,;=]|%[0-9a-f]{2})*)";
        $r .=   "(?::(\d*))?";
        $r .=   "(/(?:[a-z0-9-._~!$&'()*+,;=:@/]|%[0-9a-f]{2})*)?";
        $r .=   "|";
        $r .=   "(/?";
        $r .=     "(?:[a-z0-9-._~!$&'()*+,;=:@]|%[0-9a-f]{2})+";
        $r .=     "(?:[a-z0-9-._~!$&'()*+,;=:@\/]|%[0-9a-f]{2})*";
        $r .=    ")?";
        $r .= ")";
        $r .= "(?:\?((?:[a-z0-9-._~!$&'()*+,;=:\/?@]|%[0-9a-f]{2})*))?";
        $r .= "(?:#((?:[a-z0-9-._~!$&'()*+,;=:\/?@]|%[0-9a-f]{2})*))?";
        preg_match("`$r`i", $url, $match);
        $parts = array(
            "scheme"=>'',
            "userinfo"=>'',
            "authority"=>'',
            "host"=> '',
            "port"=>'',
            "path"=>'',
            "query"=>'',
            "fragment"=>'');
        switch (count ($match)) {
            case 10: $parts['fragment'] = $match[9];
            case 9: $parts['query'] = $match[8];
            case 8: $parts['path'] =  $match[7];
            case 7: $parts['path'] =  $match[6] . $parts['path'];
            case 6: $parts['port'] =  $match[5];
            case 5: $parts['host'] =  $match[3]?"[".$match[3]."]":$match[4];
            case 4: $parts['userinfo'] =  $match[2];
            case 3: $parts['scheme'] =  $match[1];
        }
        $parts['authority'] = ($parts['userinfo']?$parts['userinfo']."@":"").
            $parts['host'].
            ($parts['port']?":".$parts['port']:"");
        return $parts;
    }

}
