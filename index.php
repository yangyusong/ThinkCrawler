<?php
/**
 * @author YangYuSong
 * Date: 15-1-1
 * www or no www is same content
 */
require('crawler_lib/curl.class.php');
require_once('crawler_lib/domain.class.php');
require('url.class.php');
require('crawler_lib/mysql/db_auid.php');
require('crawler_lib/file_lib.class.php');
require_once('config.php');
$cc = new cURL();

function getByUrl($config, $currentDomain, $cc)
{
    $content = $cc->get($config['crawlerPath']);
    preg_match_all($config['urlPattern'], $content, $match);
//print_r($match[1]);
    if (count($match) > 1) {
        $goodUrls = url::dropBadUrls($match[1]);
        $singleUrls = url::delMulti($goodUrls);
        print_r($singleUrls);
        $absoluteUrls = url::toAbsPath($singleUrls, $config['crawlerPath']);
        print_r($absoluteUrls);
        $domainUrls = domain::getUrlsByDomain($absoluteUrls, $currentDomain);
        print_r($domainUrls);
        add('urls', array('url', 'domain', 'visited'), array($config['crawlerPath'], $currentDomain, 1));
        saveFile($currentDomain, $config['crawlerPath'], $content);
        foreach ($domainUrls as $domainUrl) {
            add('urls', array('url', 'domain', 'visited'), array($domainUrl, $currentDomain, 2));
        }

        getByDbDomain($config, $currentDomain, $cc);

//    print_r($domainUrls);
    } else {
        echo 'no web need to get';
    }
}


function getByDbDomain($config, $currentDomain, $cc)
{
    $oldUrlsArr = query_eqs('urls', array('domain' => $currentDomain, 'visited' => 2));
    $oldUrls = array();
    $allUrls = array();
    foreach ($oldUrlsArr as $oldUrlsOne) {
        $oldUrls[] = $oldUrlsOne['url'];
    }

    if (count($oldUrls) == 0) {
        echo 'finish all';
        return;
    }

    foreach ($oldUrls as $oldUrl) {
        $allUrlsArr = query_eqs('urls', array('domain' => $currentDomain));
        foreach ($allUrlsArr as $allUrl) {
            $allUrls[$allUrl['url']] = $allUrl['url'];
        }
        $content = $cc->get($oldUrl);
        update('urls', array('visited' => 1), array('domain' => $currentDomain, 'url' => $oldUrl));
        echo $oldUrl."\n";
        saveFile($currentDomain, $oldUrl, $content);

        preg_match_all($config['urlPattern'], $content, $match);
//print_r($match[1]);
        if (count($match) > 1) {
            $goodUrls = url::dropBadUrls($match[1]);

            $singleUrls = url::delMulti($goodUrls);
            $absoluteUrls = url::toAbsPath($singleUrls, $oldUrl);
            $domainUrls = domain::getUrlsByDomain($absoluteUrls, $currentDomain);
            $needSaveUrls = array_diff_key($domainUrls, $allUrls);
            print_r($domainUrls);
            print_r($allUrls);
            print_r($needSaveUrls);

            foreach ($needSaveUrls as $needSaveUrl) {
                add('urls', array('url', 'domain', 'visited'), array($needSaveUrl, $currentDomain, 2));
            }
//                print_r($needSaveUrls);
        }
    }

    sleep(2);
    getByDbDomain($config, $currentDomain, $cc);

}

function saveFile($currentDomain, $url, $content){
    $dirName = 'dest/' . $currentDomain;
    if (!file_exists($dirName)) {
        mkdir($dirName);
    }
    $file_name  = str_replace('/', '_', $url);
    $file_name  = str_replace(':', '_', $file_name);
    $file_name  = str_replace('?', '_', $file_name);
    $file_name  = str_replace('&', '_', $file_name);
    file_lib::wt($dirName . '/' . $file_name, $content);
}

$currentDomain = domain::getDomain($config['crawlerPath']);
getByUrl($config, $currentDomain, $cc);

//    $nameJson = json_decode($nameStr);

?>

