-- use you test db
CREATE TABLE IF NOT EXISTS `urls` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `url` text NOT NULL COMMENT 'url',
  `domain` varchar(200) NOT NULL COMMENT '主域名',
  `visited` int(11) NOT NULL COMMENT '是否访问过 1:是,2:否',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='url访问表' AUTO_INCREMENT=718 ;