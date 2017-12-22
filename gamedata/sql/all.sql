-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2017 �?12 �?17 �?12:17
-- 服务器版�?: 5.5.53
-- PHP 版本: 5.6.27

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据�?: `acdts_2`
--

-- --------------------------------------------------------

--
-- 表的结构 `acbra2_chat`
--

DROP TABLE IF EXISTS `acbra2_chat`;
CREATE TABLE IF NOT EXISTS `acbra2_chat` (
  `cid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `send` char(30) NOT NULL DEFAULT '',
  `recv` char(15) NOT NULL DEFAULT '',
  `msg` char(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`cid`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `acbra2_game`
--

DROP TABLE IF EXISTS `acbra2_game`;
CREATE TABLE IF NOT EXISTS `acbra2_game` (
  `gamenum` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `gametype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `gamestate` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `groomid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `groomtype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `groomstatus` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `afktime` int(10) unsigned NOT NULL DEFAULT '0',
  `validnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `alivenum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `deathnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `combonum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `weather` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `hack` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hdamage` int(10) unsigned NOT NULL DEFAULT '0',
  `hplayer` char(15) NOT NULL DEFAULT '',
  `winmode` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `winner` char(15) NOT NULL DEFAULT '',
  `areanum` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `areatime` int(10) unsigned NOT NULL DEFAULT '0',
  `areawarn` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `arealist` varchar(255) NOT NULL DEFAULT '',
  `noisevars` varchar(1000) NOT NULL DEFAULT '',
  `roomvars` text NOT NULL,
  `gamevars` text NOT NULL,
  PRIMARY KEY (`groomid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `acbra2_history`
--

DROP TABLE IF EXISTS `acbra2_history`;
CREATE TABLE IF NOT EXISTS `acbra2_history` (
  `gid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `wmode` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `winner` char(15) NOT NULL DEFAULT '',
  `motto` char(30) NOT NULL DEFAULT '',
  `gametype` tinyint(3) NOT NULL DEFAULT '0',
  `vnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `gtime` int(10) unsigned NOT NULL DEFAULT '0',
  `gstime` int(10) unsigned NOT NULL DEFAULT '0',
  `getime` int(10) unsigned NOT NULL DEFAULT '0',
  `hdmg` int(10) unsigned NOT NULL DEFAULT '0',
  `hdp` char(15) NOT NULL DEFAULT '',
  `hkill` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hkp` char(15) NOT NULL DEFAULT '',
  `winnernum` tinyint(3) NOT NULL DEFAULT '0',
  `winnerteamID` char(20) NOT NULL DEFAULT '',
  `winnerlist` varchar(1000) NOT NULL DEFAULT '',
  `winnerpdata` mediumtext NOT NULL,
  `validlist` text NOT NULL,
  `hnews` mediumtext NOT NULL,
  UNIQUE KEY `gid` (`gid`),
  KEY `WMODE` (`wmode`),
  KEY `WINNER` (`winner`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `acbra2_log`
--

DROP TABLE IF EXISTS `acbra2_log`;
CREATE TABLE IF NOT EXISTS `acbra2_log` (
  `lid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `toid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `type` char(1) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `log` text NOT NULL,
  PRIMARY KEY (`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `acbra2_mapitem`
--

DROP TABLE IF EXISTS `acbra2_mapitem`;
CREATE TABLE IF NOT EXISTS `acbra2_mapitem` (
  `iid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `itm` char(30) NOT NULL DEFAULT '',
  `itmk` char(5) NOT NULL DEFAULT '',
  `itme` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms` char(5) NOT NULL DEFAULT '0',
  `itmsk` char(5) NOT NULL DEFAULT '',
  `pls` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`iid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7757 ;

-- --------------------------------------------------------

--
-- 表的结构 `acbra2_maptrap`
--

DROP TABLE IF EXISTS `acbra2_maptrap`;
CREATE TABLE IF NOT EXISTS `acbra2_maptrap` (
  `tid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `itm` char(30) NOT NULL DEFAULT '',
  `itmk` char(5) NOT NULL DEFAULT '',
  `itme` smallint(5) unsigned NOT NULL DEFAULT '0',
  `itms` char(5) NOT NULL DEFAULT '0',
  `itmsk` char(5) NOT NULL DEFAULT '',
  `pls` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=171 ;

-- --------------------------------------------------------

--
-- 表的结构 `acbra2_newsinfo`
--

DROP TABLE IF EXISTS `acbra2_newsinfo`;
CREATE TABLE IF NOT EXISTS `acbra2_newsinfo` (
  `nid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `news` char(15) NOT NULL DEFAULT '',
  `a` varchar(255) NOT NULL DEFAULT '',
  `b` varchar(255) NOT NULL DEFAULT '',
  `c` varchar(255) NOT NULL DEFAULT '',
  `d` varchar(255) NOT NULL DEFAULT '',
  `e` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`nid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `acbra2_players`
--

DROP TABLE IF EXISTS `acbra2_players`;
CREATE TABLE IF NOT EXISTS `acbra2_players` (
  `pid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` char(15) NOT NULL DEFAULT '',
  `pass` char(32) NOT NULL DEFAULT '',
  `ip` char(15) NOT NULL DEFAULT '',
  `player_dead_flag` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `corpse_clear_flag` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `gd` char(1) NOT NULL DEFAULT 'm',
  `sNo` smallint(5) unsigned NOT NULL DEFAULT '0',
  `club` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `validtime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `cdsec` int(10) unsigned NOT NULL DEFAULT '0',
  `cdmsec` smallint(3) unsigned NOT NULL DEFAULT '0',
  `cdtime` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `action` char(12) NOT NULL DEFAULT '',
  `a_actionnum` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `v_actionnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hp` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `mhp` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `sp` smallint(5) unsigned NOT NULL DEFAULT '0',
  `msp` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ss` smallint(5) unsigned NOT NULL DEFAULT '0',
  `mss` smallint(5) unsigned NOT NULL DEFAULT '0',
  `att` smallint(5) unsigned NOT NULL DEFAULT '0',
  `def` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pls` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `lvl` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `exp` smallint(5) unsigned NOT NULL DEFAULT '0',
  `money` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rp` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `bid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `inf` char(10) NOT NULL DEFAULT '',
  `rage` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pose` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tactic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `killnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `skillpoint` smallint(5) NOT NULL DEFAULT '0',
  `flare` tinyint(1) NOT NULL DEFAULT '0',
  `wp` int(10) unsigned NOT NULL DEFAULT '0',
  `wk` int(10) unsigned NOT NULL DEFAULT '0',
  `wg` int(10) unsigned NOT NULL DEFAULT '0',
  `wc` int(10) unsigned NOT NULL DEFAULT '0',
  `wd` int(10) unsigned NOT NULL DEFAULT '0',
  `wf` int(10) unsigned NOT NULL DEFAULT '0',
  `icon` varchar(255) NOT NULL DEFAULT '0',
  `teamID` char(15) NOT NULL DEFAULT '',
  `teamPass` char(15) NOT NULL DEFAULT '',
  `card` int(10) NOT NULL DEFAULT '0',
  `cardname` varchar(50) NOT NULL DEFAULT '',
  `wep` varchar(30) NOT NULL DEFAULT '',
  `wepk` char(5) NOT NULL DEFAULT '',
  `wepe` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `weps` char(5) NOT NULL DEFAULT '0',
  `wepsk` varchar(40) NOT NULL DEFAULT '',
  `arb` varchar(30) NOT NULL DEFAULT '',
  `arbk` char(5) NOT NULL DEFAULT '',
  `arbe` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `arbs` char(5) NOT NULL DEFAULT '0',
  `arbsk` varchar(40) NOT NULL DEFAULT '',
  `arh` varchar(30) NOT NULL DEFAULT '',
  `arhk` char(5) NOT NULL DEFAULT '',
  `arhe` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `arhs` char(5) NOT NULL DEFAULT '0',
  `arhsk` varchar(40) NOT NULL DEFAULT '',
  `ara` varchar(30) NOT NULL DEFAULT '',
  `arak` char(5) NOT NULL DEFAULT '',
  `arae` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `aras` char(5) NOT NULL DEFAULT '0',
  `arask` varchar(40) NOT NULL DEFAULT '',
  `arf` varchar(30) NOT NULL DEFAULT '',
  `arfk` char(5) NOT NULL DEFAULT '',
  `arfe` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `arfs` char(5) NOT NULL DEFAULT '0',
  `arfsk` varchar(40) NOT NULL DEFAULT '',
  `art` varchar(30) NOT NULL DEFAULT '',
  `artk` varchar(40) NOT NULL DEFAULT '',
  `arte` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `arts` char(5) NOT NULL DEFAULT '0',
  `artsk` varchar(40) NOT NULL DEFAULT '',
  `itm0` varchar(30) NOT NULL DEFAULT '',
  `itmk0` char(5) NOT NULL DEFAULT '',
  `itme0` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms0` char(5) NOT NULL DEFAULT '0',
  `itmsk0` varchar(40) NOT NULL DEFAULT '',
  `itm1` varchar(30) NOT NULL DEFAULT '',
  `itmk1` char(5) NOT NULL DEFAULT '',
  `itme1` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms1` char(5) NOT NULL DEFAULT '0',
  `itmsk1` varchar(40) NOT NULL DEFAULT '',
  `itm2` varchar(30) NOT NULL DEFAULT '',
  `itmk2` char(5) NOT NULL DEFAULT '',
  `itme2` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms2` char(5) NOT NULL DEFAULT '0',
  `itmsk2` varchar(40) NOT NULL DEFAULT '',
  `itm3` varchar(30) NOT NULL DEFAULT '',
  `itmk3` char(5) NOT NULL DEFAULT '',
  `itme3` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms3` char(5) NOT NULL DEFAULT '0',
  `itmsk3` varchar(40) NOT NULL DEFAULT '',
  `itm4` varchar(30) NOT NULL DEFAULT '',
  `itmk4` char(5) NOT NULL DEFAULT '',
  `itme4` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms4` char(5) NOT NULL DEFAULT '0',
  `itmsk4` varchar(40) NOT NULL DEFAULT '',
  `itm5` varchar(30) NOT NULL DEFAULT '',
  `itmk5` char(5) NOT NULL DEFAULT '',
  `itme5` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms5` char(5) NOT NULL DEFAULT '0',
  `itmsk5` varchar(40) NOT NULL DEFAULT '',
  `itm6` varchar(30) NOT NULL DEFAULT '',
  `itmk6` char(5) NOT NULL DEFAULT '',
  `itme6` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms6` char(5) NOT NULL DEFAULT '0',
  `itmsk6` varchar(40) NOT NULL DEFAULT '',
  `searchmemory` text NOT NULL,
  `nskill` text NOT NULL,
  `nskillpara` text NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `TYPE` (`type`),
  KEY `NAME` (`name`),
  KEY `PLS` (`pls`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=366 ;

-- --------------------------------------------------------

--
-- 表的结构 `acbra2_roomlisteners`
--

DROP TABLE IF EXISTS `acbra2_roomlisteners`;
CREATE TABLE IF NOT EXISTS `acbra2_roomlisteners` (
  `port` int(10) unsigned NOT NULL DEFAULT '0',
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `roomid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniqid` char(35) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `acbra2_shopitem`
--

DROP TABLE IF EXISTS `acbra2_shopitem`;
CREATE TABLE IF NOT EXISTS `acbra2_shopitem` (
  `sid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `kind` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `num` smallint(5) unsigned NOT NULL DEFAULT '0',
  `price` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `area` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `item` varchar(30) NOT NULL DEFAULT '',
  `itmk` char(5) NOT NULL DEFAULT '',
  `itme` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms` char(5) NOT NULL DEFAULT '0',
  `itmsk` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`sid`),
  KEY `KIND` (`kind`,`area`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=180 ;

-- --------------------------------------------------------

--
-- 表的结构 `acbra2_users`
--

DROP TABLE IF EXISTS `acbra2_users`;
CREATE TABLE IF NOT EXISTS `acbra2_users` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(15) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `alt_pswd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `groupid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `roomid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `gender` char(1) NOT NULL DEFAULT '0',
  `motto` char(30) NOT NULL DEFAULT '',
  `killmsg` char(30) NOT NULL DEFAULT '',
  `lastword` char(30) NOT NULL DEFAULT '',
  `lastwin` int(10) unsigned NOT NULL DEFAULT '0',
  `lastgame` smallint(5) unsigned NOT NULL DEFAULT '0',
  `validgames` smallint(5) unsigned NOT NULL DEFAULT '0',
  `wingames` smallint(5) unsigned NOT NULL DEFAULT '0',
  `credits` int(10) NOT NULL DEFAULT '0',
  `totalcredits` int(10) NOT NULL DEFAULT '0',
  `credits2` mediumint(9) NOT NULL DEFAULT '0',
  `gold` int(10) unsigned NOT NULL DEFAULT '0',
  `elo_rating` int(10) unsigned NOT NULL DEFAULT '1500',
  `elo_volatility` int(10) unsigned NOT NULL DEFAULT '400',
  `elo_playedtimes` int(10) unsigned NOT NULL DEFAULT '0',
  `card` int(10) unsigned NOT NULL DEFAULT '0',
  `cd_s` int(10) unsigned NOT NULL DEFAULT '0',
  `cd_a` int(10) unsigned NOT NULL DEFAULT '0',
  `cd_a1` int(10) unsigned NOT NULL DEFAULT '0',
  `cd_b` int(10) unsigned NOT NULL DEFAULT '0',
  `cardenergylastupd` int(10) unsigned NOT NULL DEFAULT '0',
  `u_templateid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `icon` varchar(255) NOT NULL DEFAULT '0',
  `cardenergy` text NOT NULL,
  `cardlist` text NOT NULL,
  `elo_history` text NOT NULL,
  `u_achievements` text NOT NULL,
  `n_achievements` text NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
