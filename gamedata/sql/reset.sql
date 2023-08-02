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

--
-- 表的结构 `bra_chat`
-- 公聊 0，队�? 1，私�? 2 ，系�? 3，公�? 4�?
--

DROP TABLE IF EXISTS bra_chat;
CREATE TABLE bra_chat (
  cid mediumint unsigned NOT NULL auto_increment,
  type tinyint(1) unsigned NOT NULL default '0',
 `time` int(10) unsigned NOT NULL default '0',
  send char(30) NOT NULL default '',
  recv char(15) NOT NULL default '',
  msg char(60) NOT NULL default '',

  PRIMARY KEY  (cid)
) ENGINE=HEAP;

--
-- 表的结构 `bra_mapitem`
-- 储存地图道具的信�?
--

DROP TABLE IF EXISTS bra_mapitem;
CREATE TABLE bra_mapitem (
  iid mediumint unsigned NOT NULL auto_increment,
  itm varchar(30) NOT NULL default '',
  itmk varchar(15) not null default '',
  itme int(10) unsigned NOT NULL default '0',
  itms varchar(10) not null default '0',
  itmsk varchar(40) not null default '',
  pls tinyint unsigned not null default '0',
  
  PRIMARY KEY  (iid)
) ENGINE=MyISAM;

--
-- 表的结构 `bra_maptrap`
-- 储存地图陷阱的信�?
--

DROP TABLE IF EXISTS bra_maptrap;
CREATE TABLE bra_maptrap (
  tid mediumint unsigned NOT NULL auto_increment,
  itm varchar(30) NOT NULL default '',
  itmk varchar(15) not null default '',
  itme int(10) unsigned NOT NULL default '0',
  itms varchar(10) not null default '0',
  itmsk varchar(40) not null default '',
  pls tinyint unsigned not null default '0',
  
  PRIMARY KEY  (tid)
) ENGINE=MyISAM;

--
-- 表的结构 `bra_newsinfo`
-- 储存进行状况的信�?
--

DROP TABLE IF EXISTS bra_newsinfo;
CREATE TABLE bra_newsinfo (
  nid mediumint unsigned NOT NULL auto_increment,
 `time` int(10) unsigned NOT NULL default '0',
 `news` varchar(20) NOT NULL default '',
 `a` text NOT NULL default '',
 `b` varchar(255) NOT NULL default '',
 `c` varchar(255) NOT NULL default '',
 `d` varchar(255) NOT NULL default '',
 `e` varchar(255) NOT NULL default '',

  PRIMARY KEY  (nid)
) ENGINE=MyISAM;