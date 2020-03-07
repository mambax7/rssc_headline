# $Id: mysql.sql,v 1.2 2011/12/29 20:06:57 ohwada Exp $

# 2011-12-29 K.OHWADA
# TYPE=MyISAM -> ENGINE=MyISAM
# BLOB/TEXT can't have a default value

# 2006-07-02 K.OHWADA
# change xoopsheadline to rssc_headline
# add field headline_rssc_lid

#=========================================================
# RSSC HeadLine
# 2006-07-02 K.OHWADA
#=========================================================
# based on xoopsHeadline
#=========================================================

CREATE TABLE rssc_headline (
  headline_id smallint(3) unsigned NOT NULL auto_increment,
  headline_name varchar(255) NOT NULL default '',
  headline_url varchar(255) NOT NULL default '',
  headline_rssurl varchar(255) NOT NULL default '',
  headline_encoding varchar(15) NOT NULL default '',
  headline_cachetime mediumint(8) unsigned NOT NULL default '3600',
  headline_asblock tinyint(1) unsigned NOT NULL default '0',
  headline_display tinyint(1) unsigned NOT NULL default '0',
  headline_weight smallint(3) unsigned NOT NULL default '0',
  headline_mainfull tinyint(1) unsigned NOT NULL default '1',
  headline_mainimg tinyint(1) unsigned NOT NULL default '1',
  headline_mainmax tinyint(2) unsigned NOT NULL default '10',
  headline_blockimg tinyint(1) unsigned NOT NULL default '0',
  headline_blockmax tinyint(2) unsigned NOT NULL default '10',
  headline_xml text NOT NULL,
  headline_updated int(10) NOT NULL default'0',
  headline_rssc_lid int(10) unsigned NOT NULL default'0',
  PRIMARY KEY  (headline_id),
  KEY headline_rssc_lid (headline_rssc_lid)
) ENGINE=MyISAM;

