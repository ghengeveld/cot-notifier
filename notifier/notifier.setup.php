<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
http://www.neocrome.net

[BEGIN_SED]
File=plugins/notifier/notifier.setup.php
Version=121
Updated=2007-jul-30
Type=Plugin
Author=Neocrome
Description=Notifier plugin ported to Seditio by Koradhil
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=notifier
Name=Notifier
Description=Members can subscribe to topics in forums, and receive notification of updates.
Version=1.0
Date=2007-jul-30
Author=Neocrome, ported to Seditio by Koradhil
Copyright=
Notes=
SQL=
Lock_guests=RW12345A
Auth_members=RW
Lock_members=RW12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
allowpms=03:radio::1:Allow notification by PM
allowemails=04:radio::1:Allow notification by email
maxitems=05:string:10:10:Maximum number of subscriptions
autoprune=06:string:15:15:Auto remove subscriptions topics after * days in idle state
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if ( !defined('SED_CODE') ) { die("Wrong URL."); }

?>
