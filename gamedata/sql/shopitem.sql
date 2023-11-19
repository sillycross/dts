--
-- 表的结构 `bra_shopitem`
--

DROP TABLE IF EXISTS bra_shopitem;
CREATE TABLE bra_shopitem (
  sid smallint unsigned NOT NULL auto_increment,
  kind tinyint unsigned NOT NULL default '0',
  num smallint unsigned NOT NULL default '0',
  price mediumint(8) unsigned NOT NULL default '0',
  area tinyint unsigned NOT NULL default '0',
  item varchar(30) NOT NULL default '',
  itmk char(5) NOT NULL default '',
  itme mediumint(8) unsigned NOT NULL default '0',
  itms char(5) NOT NULL default '0',
  itmsk text NOT NULL default '',

  PRIMARY KEY  (sid),
  INDEX KIND (kind, area)
) ENGINE=MyISAM;
