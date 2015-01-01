<?php
/**
 * @author YangYuSong
 * Date: 15-1-1
 */
require('crawler_lib/curl.class.php');
require('crawler_lib/domain.class.php');
require('url.class.php');
require('crawler_lib/mysql/db_auid.php');
$cc = new cURL();
$crawlerPath = "http://news.china.com/domestic/945/20150101/19166957.html";
$currentDomain = domain::getDomain($crawlerPath);

$urlPattern = '/href\s*=\s*[\'\"](.*?)[\'\"]/m';

$content = $cc->get($crawlerPath);
preg_match_all($urlPattern, $content, $match);
//print_r($match[1]);
if(count($match) > 1)
{
    $goodUrls = url::dropBadUrls($match[1]);
    $absoluteUrls = url::toAbsolutePath($goodUrls, $crawlerPath);
    $domainUrls = domain::getUrlsByDomain($absoluteUrls, $currentDomain);

    foreach ($domainUrls as $domainUrl) {
        add('urls', array('url', 'domain', 'visited'), array($domainUrl, $currentDomain, 1));
    }

    print_r($domainUrls);
}

//echo "ok";
//print_r($domainUrls);
//echo $content;
//    $nameJson = json_decode($nameStr);

?>

