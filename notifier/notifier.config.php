<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
http://www.neocrome.net

[BEGIN_SED]
File=plugins/notifier/notifier.config.php
Version=121
Updated=2007-jul-30
Type=Plugin
Author=Neocrome
Description=Notifier plugin ported to Seditio by Koradhil
[END_SED]
==================== */

if ( !defined('SED_CODE') ) { die("Wrong URL."); }

$db_notifier = 'sed_notifier';

$bgcolor[0] = "DDDDDD"; // Hex color for 'idle'
$bgcolor[1] = "BBBBBB"; // Hex color for 'notified by email'
$bgcolor[2] = "999999"; // Hex color for 'notified by email'

$notstate[0] = $L['plu_state_idle'];
$notstate[1] = $L['plu_state_notifiedemail'];
$notstate[2] = $L['plu_state_notifiedpm'];

$notby[0] = $L['plu_email'];
$notby[1] = $L['plu_pm'];

// $nottype['p'] = $L['Page'];
// $nottype['l'] = $L['Link'];
$nottype['f'] = $L['Forums'];
// $nottype['e'] = $L['Event'];
// $nottype['n'] = $L['Newspost'];

// $noticon['p'] = 'system/img/admin/pages.gif';
// $noticon['l'] = 'system/img/admin/links.gif';
$noticon['f'] = 'system/img/admin/forums.gif';
// $noticon['e'] = 'system/img/admin/events.gif';
// $noticon['n'] = 'system/img/admin/news.gif';

?>
