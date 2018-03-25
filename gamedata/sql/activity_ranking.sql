--
-- 表的结构 `bra_activity_ranking`
--

DROP TABLE IF EXISTS bra_activity_ranking;
CREATE TABLE bra_activity_ranking (
  pid mediumint unsigned NOT NULL auto_increment,
  username char(15) NOT NULL default '',
  score1 int unsigned NOT NULL default '0',
  score2 int unsigned NOT NULL default '0',
  atime int unsigned NOT NULL default '0',

  PRIMARY KEY  (pid),
  UNIQUE KEY NAME (username),
  INDEX SCORE (score1,score2)
) ENGINE=MyISAM;