-- MySQL dump 10.15  Distrib 10.0.17-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: test
-- ------------------------------------------------------
-- Server version	10.0.17-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acbra2_chat`
--

DROP TABLE IF EXISTS `acbra2_chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acbra2_chat` (
  `cid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('0','1','2','3','4','5') NOT NULL DEFAULT '0',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `send` char(24) NOT NULL DEFAULT '',
  `recv` char(15) NOT NULL DEFAULT '',
  `msg` char(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`cid`)
) ENGINE=MEMORY AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acbra2_gambling`
--

DROP TABLE IF EXISTS `acbra2_gambling`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acbra2_gambling` (
  `gid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uname` char(15) NOT NULL DEFAULT '',
  `bid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `bname` char(15) NOT NULL DEFAULT '',
  `wager` int(10) unsigned NOT NULL DEFAULT '0',
  `odds` decimal(8,4) unsigned NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acbra2_game`
--

DROP TABLE IF EXISTS `acbra2_game`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acbra2_game` (
  `gamenum` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `gametype` int(10) NOT NULL DEFAULT '0',
  `gamestate` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `winmode` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `winner` char(15) NOT NULL DEFAULT '',
  `arealist` varchar(255) NOT NULL DEFAULT '',
  `areanum` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `areatime` int(10) unsigned NOT NULL DEFAULT '0',
  `areawarn` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `validnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `alivenum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `deathnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `afktime` int(10) unsigned NOT NULL DEFAULT '0',
  `optime` int(10) unsigned NOT NULL DEFAULT '0',
  `weather` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `hack` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `combonum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `gamevars` int(10) unsigned NOT NULL DEFAULT '0',
  `rdown` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `bdown` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ldown` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `kdown` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `gameotherinfo` char(255) NOT NULL DEFAULT '',
  `is_solo` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gamenum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acbra2_log`
--

DROP TABLE IF EXISTS `acbra2_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acbra2_log` (
  `lid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `toid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `type` char(1) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `log` text NOT NULL,
  PRIMARY KEY (`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acbra2_mapitem`
--

DROP TABLE IF EXISTS `acbra2_mapitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acbra2_mapitem` (
  `iid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `itm` char(30) NOT NULL DEFAULT '',
  `itmk` char(5) NOT NULL DEFAULT '',
  `itme` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms` char(5) NOT NULL DEFAULT '0',
  `itmsk` char(5) NOT NULL DEFAULT '',
  `pls` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`iid`)
) ENGINE=MyISAM AUTO_INCREMENT=7527 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acbra2_maptrap`
--

DROP TABLE IF EXISTS `acbra2_maptrap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acbra2_maptrap` (
  `tid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `itm` char(30) NOT NULL DEFAULT '',
  `itmk` char(5) NOT NULL DEFAULT '',
  `itme` smallint(5) unsigned NOT NULL DEFAULT '0',
  `itms` char(5) NOT NULL DEFAULT '0',
  `itmsk` char(5) NOT NULL DEFAULT '',
  `pls` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM AUTO_INCREMENT=171 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acbra2_newsinfo`
--

DROP TABLE IF EXISTS `acbra2_newsinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acbra2_newsinfo` (
  `nid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `news` char(15) NOT NULL DEFAULT '',
  `a` varchar(255) NOT NULL DEFAULT '',
  `b` varchar(255) NOT NULL DEFAULT '',
  `c` varchar(255) NOT NULL DEFAULT '',
  `d` varchar(255) NOT NULL DEFAULT '',
  `e` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`nid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acbra2_players`
--

DROP TABLE IF EXISTS `acbra2_players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acbra2_players` (
  `pid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `name` char(15) NOT NULL DEFAULT '',
  `pass` char(32) NOT NULL DEFAULT '',
  `gd` char(1) NOT NULL DEFAULT 'm',
  `sNo` smallint(5) unsigned NOT NULL DEFAULT '0',
  `icon` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `club` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `cdsec` int(10) unsigned NOT NULL DEFAULT '0',
  `cdmsec` smallint(3) unsigned NOT NULL DEFAULT '0',
  `cdtime` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `action` char(12) NOT NULL DEFAULT '',
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
  `bid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `inf` char(10) NOT NULL DEFAULT '',
  `rage` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pose` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tactic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `killnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `wp` smallint(5) unsigned NOT NULL DEFAULT '0',
  `wk` smallint(5) unsigned NOT NULL DEFAULT '0',
  `wg` smallint(5) unsigned NOT NULL DEFAULT '0',
  `wc` smallint(5) unsigned NOT NULL DEFAULT '0',
  `wd` smallint(5) unsigned NOT NULL DEFAULT '0',
  `wf` smallint(5) unsigned NOT NULL DEFAULT '0',
  `teamID` char(15) NOT NULL DEFAULT '',
  `teamPass` char(15) NOT NULL DEFAULT '',
  `wep` char(30) NOT NULL DEFAULT '',
  `wepk` char(5) NOT NULL DEFAULT '',
  `wepe` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `weps` char(5) NOT NULL DEFAULT '0',
  `wepsk` char(5) NOT NULL DEFAULT '',
  `arb` char(30) NOT NULL DEFAULT '',
  `arbk` char(5) NOT NULL DEFAULT '',
  `arbe` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `arbs` char(5) NOT NULL DEFAULT '0',
  `arbsk` char(5) NOT NULL DEFAULT '',
  `arh` char(30) NOT NULL DEFAULT '',
  `arhk` char(5) NOT NULL DEFAULT '',
  `arhe` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `arhs` char(5) NOT NULL DEFAULT '0',
  `arhsk` char(5) NOT NULL DEFAULT '',
  `ara` char(30) NOT NULL DEFAULT '',
  `arak` char(5) NOT NULL DEFAULT '',
  `arae` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `aras` char(5) NOT NULL DEFAULT '0',
  `arask` char(5) NOT NULL DEFAULT '',
  `arf` char(30) NOT NULL DEFAULT '',
  `arfk` char(5) NOT NULL DEFAULT '',
  `arfe` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `arfs` char(5) NOT NULL DEFAULT '0',
  `arfsk` char(5) NOT NULL DEFAULT '',
  `art` char(30) NOT NULL DEFAULT '',
  `artk` char(5) NOT NULL DEFAULT '',
  `arte` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `arts` char(5) NOT NULL DEFAULT '0',
  `artsk` char(5) NOT NULL DEFAULT '',
  `itm0` char(30) NOT NULL DEFAULT '',
  `itmk0` char(5) NOT NULL DEFAULT '',
  `itme0` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms0` char(5) NOT NULL DEFAULT '0',
  `itmsk0` char(5) NOT NULL DEFAULT '',
  `itm1` char(30) NOT NULL DEFAULT '',
  `itmk1` char(5) NOT NULL DEFAULT '',
  `itme1` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms1` char(5) NOT NULL DEFAULT '0',
  `itmsk1` char(5) NOT NULL DEFAULT '',
  `itm2` char(30) NOT NULL DEFAULT '',
  `itmk2` char(5) NOT NULL DEFAULT '',
  `itme2` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms2` char(5) NOT NULL DEFAULT '0',
  `itmsk2` char(5) NOT NULL DEFAULT '',
  `itm3` char(30) NOT NULL DEFAULT '',
  `itmk3` char(5) NOT NULL DEFAULT '',
  `itme3` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms3` char(5) NOT NULL DEFAULT '0',
  `itmsk3` char(5) NOT NULL DEFAULT '',
  `itm4` char(30) NOT NULL DEFAULT '',
  `itmk4` char(5) NOT NULL DEFAULT '',
  `itme4` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms4` char(5) NOT NULL DEFAULT '0',
  `itmsk4` char(5) NOT NULL DEFAULT '',
  `itm5` char(30) NOT NULL DEFAULT '',
  `itmk5` char(5) NOT NULL DEFAULT '',
  `itme5` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms5` char(5) NOT NULL DEFAULT '0',
  `itmsk5` char(5) NOT NULL DEFAULT '',
  `itm6` char(30) NOT NULL DEFAULT '',
  `itmk6` char(5) NOT NULL DEFAULT '',
  `itme6` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms6` char(5) NOT NULL DEFAULT '0',
  `itmsk6` char(5) NOT NULL DEFAULT '',
  `nskill` text NOT NULL,
  `nskillpara` text NOT NULL,
  `skillpoint` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pid`),
  KEY `TYPE` (`type`,`sNo`),
  KEY `NAME` (`name`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=355 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acbra2_shopitem`
--

DROP TABLE IF EXISTS `acbra2_shopitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acbra2_shopitem` (
  `sid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `kind` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `num` smallint(5) unsigned NOT NULL DEFAULT '0',
  `price` smallint(5) unsigned NOT NULL DEFAULT '0',
  `area` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `item` char(30) NOT NULL DEFAULT '',
  `itmk` char(5) NOT NULL DEFAULT '',
  `itme` smallint(5) unsigned NOT NULL DEFAULT '0',
  `itms` char(5) NOT NULL DEFAULT '0',
  `itmsk` char(5) NOT NULL DEFAULT '',
  PRIMARY KEY (`sid`),
  KEY `KIND` (`kind`,`area`)
) ENGINE=MyISAM AUTO_INCREMENT=173 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acbra2_temp`
--

DROP TABLE IF EXISTS `acbra2_temp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acbra2_temp` (
  `sid` char(30) NOT NULL DEFAULT '',
  `value` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acbra2_users`
--

DROP TABLE IF EXISTS `acbra2_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acbra2_users` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(15) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `groupid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `lastgame` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `credits` int(10) NOT NULL DEFAULT '0',
  `credits2` mediumint(9) NOT NULL DEFAULT '0',
  `achievement` text NOT NULL,
  `nick` text NOT NULL,
  `nicks` text NOT NULL,
  `sktime` int(11) NOT NULL DEFAULT '0',
  `validgames` smallint(5) unsigned NOT NULL DEFAULT '0',
  `wingames` smallint(5) unsigned NOT NULL DEFAULT '0',
  `gender` char(1) NOT NULL DEFAULT '0',
  `icon` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `club` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `motto` char(30) NOT NULL DEFAULT '',
  `killmsg` char(30) NOT NULL DEFAULT '',
  `lastword` char(30) NOT NULL DEFAULT '',
  `oid` char(20) NOT NULL DEFAULT '',
  `can_solo` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=30392 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acbra2_winners`
--

DROP TABLE IF EXISTS `acbra2_winners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acbra2_winners` (
  `gid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` char(15) NOT NULL DEFAULT '',
  `pass` char(32) NOT NULL DEFAULT '',
  `gd` char(1) NOT NULL DEFAULT 'm',
  `sNo` smallint(5) unsigned NOT NULL DEFAULT '0',
  `icon` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `club` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `hp` smallint(5) unsigned NOT NULL DEFAULT '0',
  `mhp` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sp` smallint(5) unsigned NOT NULL DEFAULT '0',
  `msp` smallint(5) unsigned NOT NULL DEFAULT '0',
  `att` smallint(5) unsigned NOT NULL DEFAULT '0',
  `def` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pls` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `lvl` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `exp` smallint(5) unsigned NOT NULL DEFAULT '0',
  `money` smallint(5) unsigned NOT NULL DEFAULT '0',
  `bid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `inf` char(10) NOT NULL DEFAULT '',
  `rage` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pose` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tactic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `killnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `killnum2` smallint(5) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `wp` smallint(5) unsigned NOT NULL DEFAULT '0',
  `wk` smallint(5) unsigned NOT NULL DEFAULT '0',
  `wg` smallint(5) unsigned NOT NULL DEFAULT '0',
  `wc` smallint(5) unsigned NOT NULL DEFAULT '0',
  `wd` smallint(5) unsigned NOT NULL DEFAULT '0',
  `wf` smallint(5) unsigned NOT NULL DEFAULT '0',
  `teamID` char(15) NOT NULL DEFAULT '',
  `teamPass` char(15) NOT NULL DEFAULT '',
  `wep` char(30) NOT NULL DEFAULT '',
  `wepk` char(5) NOT NULL DEFAULT '',
  `wepe` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `weps` char(5) NOT NULL DEFAULT '0',
  `wepsk` char(5) NOT NULL DEFAULT '',
  `arb` char(30) NOT NULL DEFAULT '',
  `arbk` char(5) NOT NULL DEFAULT '',
  `arbe` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `arbs` char(5) NOT NULL DEFAULT '0',
  `arbsk` char(5) NOT NULL DEFAULT '',
  `arh` char(30) NOT NULL DEFAULT '',
  `arhk` char(5) NOT NULL DEFAULT '',
  `arhe` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `arhs` char(5) NOT NULL DEFAULT '0',
  `arhsk` char(5) NOT NULL DEFAULT '',
  `ara` char(30) NOT NULL DEFAULT '',
  `arak` char(5) NOT NULL DEFAULT '',
  `arae` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `aras` char(5) NOT NULL DEFAULT '0',
  `arask` char(5) NOT NULL DEFAULT '',
  `arf` char(30) NOT NULL DEFAULT '',
  `arfk` char(5) NOT NULL DEFAULT '',
  `arfe` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `arfs` char(5) NOT NULL DEFAULT '0',
  `arfsk` char(5) NOT NULL DEFAULT '',
  `art` char(30) NOT NULL DEFAULT '',
  `artk` char(5) NOT NULL DEFAULT '',
  `arte` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `arts` char(5) NOT NULL DEFAULT '0',
  `artsk` char(5) NOT NULL DEFAULT '',
  `itm0` char(30) NOT NULL DEFAULT '',
  `itmk0` char(5) NOT NULL DEFAULT '',
  `itme0` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms0` char(5) NOT NULL DEFAULT '0',
  `itmsk0` char(5) NOT NULL DEFAULT '',
  `itm1` char(30) NOT NULL DEFAULT '',
  `itmk1` char(5) NOT NULL DEFAULT '',
  `itme1` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms1` char(5) NOT NULL DEFAULT '0',
  `itmsk1` char(5) NOT NULL DEFAULT '',
  `itm2` char(30) NOT NULL DEFAULT '',
  `itmk2` char(5) NOT NULL DEFAULT '',
  `itme2` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms2` char(5) NOT NULL DEFAULT '0',
  `itmsk2` char(5) NOT NULL DEFAULT '',
  `itm3` char(30) NOT NULL DEFAULT '',
  `itmk3` char(5) NOT NULL DEFAULT '',
  `itme3` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms3` char(5) NOT NULL DEFAULT '0',
  `itmsk3` char(5) NOT NULL DEFAULT '',
  `itm4` char(30) NOT NULL DEFAULT '',
  `itmk4` char(5) NOT NULL DEFAULT '',
  `itme4` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms4` char(5) NOT NULL DEFAULT '0',
  `itmsk4` char(5) NOT NULL DEFAULT '',
  `itm5` char(30) NOT NULL DEFAULT '',
  `itmk5` char(5) NOT NULL DEFAULT '',
  `itme5` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms5` char(5) NOT NULL DEFAULT '0',
  `itmsk5` char(5) NOT NULL DEFAULT '',
  `itm6` char(30) NOT NULL DEFAULT '',
  `itmk6` char(5) NOT NULL DEFAULT '',
  `itme6` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itms6` char(5) NOT NULL DEFAULT '0',
  `itmsk6` char(5) NOT NULL DEFAULT '',
  `motto` char(30) NOT NULL DEFAULT '',
  `wmode` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `vnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `gtime` int(10) unsigned NOT NULL DEFAULT '0',
  `gstime` int(10) unsigned NOT NULL DEFAULT '0',
  `getime` int(10) unsigned NOT NULL DEFAULT '0',
  `hdmg` int(10) unsigned NOT NULL DEFAULT '0',
  `hdp` char(15) NOT NULL DEFAULT '',
  `hkill` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hkp` char(15) NOT NULL DEFAULT '',
  `gametype` int(10) NOT NULL DEFAULT '0',
  `winnum` int(11) NOT NULL DEFAULT '0',
  `namelist` char(255) NOT NULL DEFAULT '',
  `weplist` char(255) NOT NULL DEFAULT '',
  `iconlist` char(80) NOT NULL DEFAULT '',
  `gdlist` char(50) NOT NULL DEFAULT '',
  UNIQUE KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-06-28  0:07:01
