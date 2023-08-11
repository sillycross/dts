DROP TABLE IF EXISTS `bra_game`;
CREATE TABLE `bra_game` (
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
  `roomvars` text NOT NULL DEFAULT '',
  `gamevars` text NOT NULL DEFAULT '',
  PRIMARY KEY (`groomid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `bra_roomlisteners`
--

DROP TABLE IF EXISTS `bra_roomlisteners`;
CREATE TABLE `bra_roomlisteners` (
  `port` int(10) unsigned NOT NULL DEFAULT '0',
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `roomid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniqid` char(35) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `bra_messages`
--

DROP TABLE IF EXISTS `bra_messages`;
CREATE TABLE `bra_messages` (
  `mid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `rd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `checked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `receiver` char(15) NOT NULL DEFAULT '',
  `sender` char(15) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL DEFAULT '',
  `enclosure` text NOT NULL DEFAULT '',
  PRIMARY KEY (`mid`),
  INDEX `RECEIVER` (`receiver`),
  INDEX `SENDER` (`sender`)
  
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `bra_del_messages`
--

DROP TABLE IF EXISTS `bra_del_messages`;
CREATE TABLE `bra_del_messages` (
  `mid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `dtimestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `rd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `checked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `receiver` char(15) NOT NULL DEFAULT '',
  `sender` char(15) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL DEFAULT '',
  `enclosure` text NOT NULL DEFAULT '',
  PRIMARY KEY (`mid`),
  INDEX `RECEIVER` (`receiver`),
  INDEX `SENDER` (`sender`)
  
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `bra_users`
--

DROP TABLE IF EXISTS `bra_users`;
CREATE TABLE `bra_users` (
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
  `lastgame` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `lastroomgame` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `validgames` smallint(5) unsigned NOT NULL DEFAULT '0',
  `wingames` smallint(5) unsigned NOT NULL DEFAULT '0',  
  `credits` int(10) NOT NULL DEFAULT '0',
  `totalcredits` int(10) NOT NULL DEFAULT '0',
  `credits2` mediumint(9) NOT NULL DEFAULT '0',
  `gold` int(10) unsigned NOT NULL DEFAULT '0',
  `gold2` int(10) unsigned NOT NULL DEFAULT '0',  
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
  `cardenergy` text NOT NULL DEFAULT '',
  `cardlist` text NOT NULL DEFAULT '',
  `elo_history` text NOT NULL DEFAULT '',
  `u_achievements` text NOT NULL DEFAULT '',
  `n_achievements` text NOT NULL DEFAULT '',
  
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `bra_history`
--

DROP TABLE IF EXISTS `bra_history`;
CREATE TABLE `bra_history` (
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
  `winnerpdata` mediumtext NOT NULL DEFAULT '',
  `validlist` text NOT NULL DEFAULT '',
  `hnews` mediumtext NOT NULL DEFAULT '',
  
  UNIQUE KEY `gid` (`gid`),
  INDEX `WMODE` (`wmode`),
  INDEX `WINNER` (`winner`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPRESSED;