<?php
/**
 * @author YangYuSong
 * Date: 15-1-1
 * Time: 下午10:24
 */

class domain
{

    static function getDomain($url)
    {
        $host = strtolower($url);
        if (strpos($host, '/') !== false) {
            $parse = @parse_url($host);
            if(!isset($parse ['host']))
            {
                echo $host;
                exit();
            }
            $host = $parse ['host'];
        }
        $topLevelDomainDb = array('com', 'edu', 'gov', 'int',
            'mil', 'net', 'org', 'biz', 'info', 'pro', 'name',
            'museum', 'coop', 'aero', 'xxx', 'idv', 'mobi',
            'cc', 'me');
        $str = '';
        foreach ($topLevelDomainDb as $v) {
            $str .= ($str ? '|' : '') . $v;
        }

        $matchStr = "[^.]+.(?:(" . $str . ")|w{2}|((" . $str . ").w{2}))$";
        if (preg_match("/" . $matchStr . "/ies", $host, $matchs)) {
            $domain = $matchs ['0'];
        } else {
            $domain = $host;
        }
        return $domain;
    }

    static function getUrlsByDomain($urls, $domain){
        $domainUrls = array();
        foreach ($urls as $key => $url) {
            if(static::getDomain($url) === $domain)
            {
                $domainUrls[] = $url;
            }
        }

        return $domainUrls;
    }


    public function _test(){
        $str = "http://www.jb51.net/tools/zhengze.html";
        echo getdomain ( $str );
    }

}

