--
-- 表的结构 `bra_log`
-- 类型：c对话、t队友、b作战、s系统
--

DROP TABLE IF EXISTS bra_log;
CREATE TABLE bra_log (
  lid mediumint unsigned NOT NULL auto_increment,
  toid smallint unsigned NOT NULL default '0',
  type char(1) NOT NULL default '',
 `time` int(10) unsigned NOT NULL default '0',
 `log` text NOT NULL default '',

  PRIMARY KEY  (lid)
) ENGINE=MyISAM;



