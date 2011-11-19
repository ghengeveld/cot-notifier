CREATE TABLE sed_notifier (
  not_id int(11) NOT NULL auto_increment,
  not_userid int(11) default NULL,
  not_state tinyint(1) NOT NULL default '0',
  not_bypm tinyint(1) NOT NULL default '0',
  not_date int(11) NOT NULL default '0',
  not_notified int(11) NOT NULL default '0',
  not_item varchar(16) NOT NULL default '',
  not_desc varchar(128) NOT NULL default '',
  PRIMARY KEY (not_id)
) TYPE=MyISAM;

INSERT INTO sed_stats (stat_name, stat_value) VALUES ('notifier_pm', '0');
INSERT INTO sed_stats (stat_name, stat_value) VALUES ('notifier_em', '0');