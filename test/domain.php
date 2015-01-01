<?php
/**
 * @author YangYuSong
 * Date: 15-1-1
 * Time: 下午10:20
 */

function getdomain($url) {
    $host = strtolower ( $url );
    if (strpos ( $host, '/' ) !== false) {
        $parse = @parse_url ( $host );
        $host = $parse ['host'];
    }
$topleveldomaindb = array ('com', 'edu', 'gov', 'int',
    'mil', 'net', 'org', 'biz', 'info', 'pro', 'name',
    'museum', 'coop', 'aero', 'xxx', 'idv', 'mobi',
    'cc', 'me' );
$str = '';
foreach ( $topleveldomaindb as $v ) {
    $str .= ($str ? '|' : '') . $v;
}

$matchstr = "[^.]+.(?:(" . $str . ")|w{2}|((" . $str . ").w{2}))$";
if (preg_match ( "/" . $matchstr . "/ies", $host, $matchs )) {
        $domain = $matchs ['0'];
    } else {
        $domain = $host;
    }
return $domain;
}

$str = "http://www.jb51.net/tools/zhengze.html";
echo getdomain ( $str );