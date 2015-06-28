--
-- 表的结构 `bra_mapitem`
-- 储存地图道具的信息
--

DROP TABLE IF EXISTS bra_mapitem;
CREATE TABLE bra_mapitem (
  iid mediumint unsigned NOT NULL auto_increment,
  itm char(30) NOT NULL default '',
  itmk char(5) not null default '',
  itme mediumint unsigned NOT NULL default '0',
  itms char(5) not null default '0',
  itmsk char(5) not null default '',
  pls tinyint unsigned not null default '0',
  
  PRIMARY KEY  (iid)
) ENGINE=MyISAM;
