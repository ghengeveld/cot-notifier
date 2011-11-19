<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
http://www.neocrome.net

[BEGIN_SED]
File=plugins/notifier/notifier.forums.send.php
Version=121
Updated=2007-jul-30
Type=Plugin
Author=Neocrome
Description=Notifier plugin ported to Seditio by Koradhil
[END_LDU]

[BEGIN_SED_EXTPLUGIN]
Code=notifier
Part=forums
File=notifier.forums.send
Hooks=forums.posts.newpost.done
Tags=
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]

==================== */

if ( !defined('SED_CODE') ) { die("Wrong URL."); }

if ($usr['level']>=$cfg['plugin']['notifier']['notminlevel'])
	{
	$db_notifier = sed_notifier;
	require('plugins/notifier/lang/notifier.'.$lang.'.lang.php');
	require('plugins/notifier/includes/notifier.functions.php');

	$itemcode = 'f'.$q;

	$sql = sed_notifier_get_list($itemcode);

	while ($row = mysql_fetch_array($sql))
		{
		sed_notifier_send(
			$row['not_bypm'],
			$row['not_id'],
			stripslashes($row['user_email']),
			sed_cc($row['user_name']),
			$row['not_userid'],
			$L['Forums'],
			stripslashes($row['not_desc']),
			sed_cc($usr['name']),
			"forums.php?m=posts&q=".$q."&n=unread#unread",
			sed_stripbbcode($row['fp_text'])
			);
		}
		
	/* 	This code enables users to auto-subscribe when they post in a topic.
		Use the user_extra7 field in user profile to set 'Via E-mail' or 'Via PM'.
		You might need to add extra7 to the $usr array. See functions/common.php - add: $usr['extra7'] = $row['user_extra7'];
		
	$sql2 = "SELECT t.ft_title, n.not_item FROM sed_forum_topics AS t, sed_notifier AS n WHERE t.ft_id = '".$q."' LIMIT 1";
	$res = mysql_query($sql2);
	while ($row = mysql_fetch_array($res))
		{
		$desc = stripslashes($row['ft_title']);
		}
	$sql3 = mysql_query("SELECT * FROM sed_notifier WHERE not_item = '".$itemcode."' AND not_userid = '".$usr['id']."'");
	if (mysql_num_rows($sql3) == 0)
		{
		if ($usr['extra7'] == "Via E-mail") 
			{ 
			$sql4 = "INSERT INTO sed_notifier VALUES ('', '".$usr['id']."', '0', '0', '".$sys['now_offset']."', '0', '".$itemcode."', '".$desc."')";
			mysql_query($sql4);
			}
		if ($usr['extra7'] == "Via PM")
			{
			$sql4 = "INSERT INTO sed_notifier VALUES ('', '".$usr['id']."', '0', '1', '".$sys['now_offset']."', '0', '".$itemcode."', '".$desc."')";
			mysql_query($sql4);
			}
		}
	*/
	
	}

?>
