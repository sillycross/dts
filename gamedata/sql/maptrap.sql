--
-- 表的结构 `bra_maptrap`
-- 储存地图陷阱的信息
--

DROP TABLE IF EXISTS bra_maptrap;
CREATE TABLE bra_maptrap (
  tid mediumint unsigned NOT NULL auto_increment,
  itm char(30) NOT NULL default '',
  itmk char(5) not null default '',
  itme smallint unsigned NOT NULL default '0',
  itms char(5) not null default '0',
  itmsk char(5) not null default '',
  pls tinyint unsigned not null default '0',
  
  PRIMARY KEY  (tid)
) ENGINE=MyISAM;
