<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
http://www.neocrome.net

[BEGIN_SED]
File=plugins/notifier/notifier.forums.php
Version=121
Updated=2007-jul-30
Type=Plugin
Author=Neocrome
Description=Notifier plugin ported to Seditio by Koradhil
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=notifier
Part=forums
File=notifier.forums
Hooks=forums.posts.tags
Tags=forums.posts.tpl:{NOTIFIER_MONITOR}
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

	list($not_id, $not_state) = sed_notifier_get_state($usr['id'], $itemcode);

	$t->assign(array(
	"NOTIFIER_MONITOR" => sed_notify_build_box($not_id, $not_state, $itemcode)
		));
	}

?>
