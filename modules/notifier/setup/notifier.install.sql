CREATE TABLE IF NOT EXISTS `cot_notifier_subscriptions` (
  sub_id int(11) unsigned NOT NULL auto_increment,
  sub_userid int(11) unsigned NOT NULL,
  sub_area varchar(16) NOT NULL,
  sub_itemid varchar(50) NOT NULL,
  sub_desc varchar(255) NOT NULL default '',
  sub_state enum('active', 'inactive', 'paused') NOT NULL default 'active',
  sub_created int(11) unsigned NOT NULL,
  sub_updated int(11) unsigned NULL,
  sub_lastsent int(11) unsigned NULL,
  PRIMARY KEY (sub_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `cot_notifier_settings` (
  set_userid int(11) unsigned NOT NULL,
  set_area varchar(50) NOT NULL,
  set_frequency enum('first','all','daily','weekly','monthly','never') NOT NULL default 'first',
  set_updated int(11) unsigned NOT NULL,
  PRIMARY KEY  (set_userid,set_area)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;