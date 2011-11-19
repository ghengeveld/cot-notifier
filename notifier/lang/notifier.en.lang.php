<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
http://www.neocrome.net

[BEGIN_SED]
File=plugins/notifier/lang/notifier.uk.lang.php
Version=121
Updated=2007-jul-30
Type=Plugin
Author=Neocrome
Description=Notifier plugin ported to Seditio by Koradhil
[END_SED]
==================== */


$L['plu_title'] = "Notifier";
$L['plu_title_adm'] = "Notifier ".$cfg['separator']." Administration";

$L['plu_status'] = 'Status';
$L['plu_item'] = 'Subscription';
$L['plu_since'] = 'Since';
$L['plu_notified'] = 'Last notified';
$L['plu_never'] = 'never';
$L['plu_notifyby'] = 'Notify by';
$L['plu_pm'] = 'Private Message';
$L['plu_email'] = 'Email';
$L['plu_back'] = 'Back';

$L['plu_explain'] = 'You are currently subscribed to the following topics:';

$L['plu_maxreached'] = '<strong>You have reached the maximum amount of subscriptions.</strong>';

$L['plu_state_idle'] = "Idle (no new posts)";
$L['plu_state_notifiedemail'] = "Notified by email";
$L['plu_state_notifiedpm'] = "Notified by PM";

$L['plu_monitored'][0] = 'You\'re not watching this topic';
$L['plu_monitored'][1] = 'You\'re watching this topic by email';
$L['plu_monitored'][2] = 'You\'re watching this topic by PM';

$L['plu_monitor'][0] = 'Unsubscribe from this topic';
$L['plu_monitor'][1] = 'Subscribe to this topic by email';
$L['plu_monitor'][2] = 'Subscribe to this topic by PM';
$L['plu_monitor'][8] = 'View all your subscriptions';
$L['plu_monitor'][9] = 'Select an option...';

$L['plu_notifyemailtitle'] = "Reply to topic: ";
$L['plu_notifyemail'] = "Hi %1\$s,\n\nYou are receiving this email because %4\$s has replied to the topic entitled '%3\$s'.\nClick here to read it: %5\$s\n\nYou can edit your subscriptions at: %8\$s";

$L['plu_notifypmtitle'] = "Topic watch";
$L['plu_notifypm'] = "Hi %1\$s,\n\nYou are receiving this message because %4\$s has replied to the topic entitled '%3\$s'.\nClick here to read it: %5\$s\nIf you don't want to follow this topic anymore, visit: %6\$s\nIf you want to stop receiving updates from ALL topics, visit: %7\$s";

$L['plu_stats_topics'] = 'Total subscriptions';
$L['plu_stats_topics_bypm'] = 'Subscriptions by PM';
$L['plu_stats_topics_byem'] = 'Subscriptions by email';
$L['plu_stats_users'] = 'Users with at least one subscription';

$L['plu_stats_pm'] = 'Total private messages sent';
$L['plu_stats_em'] = 'Total emails sent';

?>
