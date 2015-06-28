--
-- 表的结构 `bra_info`
-- 公聊 0，队聊 1，私聊 2 ，系统 3，公告 4，
--

DROP TABLE IF EXISTS bra_newsinfo;
CREATE TABLE bra_newsinfo (
  nid smallint unsigned NOT NULL auto_increment,
 `time` int(10) unsigned NOT NULL default '0',
 `news` char(15) NOT NULL default '',
 `a` varchar(255) NOT NULL default '',
 `b` varchar(255) NOT NULL default '',
 `c` varchar(255) NOT NULL default '',
 `d` varchar(255) NOT NULL default '',
 `e` varchar(255) NOT NULL default '',

  PRIMARY KEY  (nid)
) ENGINE=MyISAM;



