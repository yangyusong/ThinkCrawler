<?php
/**
 * @author YangYuSong
 * User: Administrator
 * Date: 15-1-2
 * Time: 下午7:15
 * 使用phpQuery轻松采集网页内容
http://www.daimajiayuan.com/sitejs-17810-1.html
 */

//引入文件
require_once 'phpQuery.php';
//target引入页面文件
phpQuery::newDocumentHTML ( $target ['content'] );
//使用query获取数据
$data['file_name'] = pq( 'dl:eq(3) dd:eq(0)' )->text();
$data['img'] = pq( '.pics3' )->attr( 'src' );

//结束,
phpQuery::unloadDocuments();