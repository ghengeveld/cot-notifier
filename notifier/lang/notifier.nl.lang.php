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


$L['plu_title'] = "Abonnementen";
$L['plu_title_adm'] = "Abonnementen ".$cfg['separator']." Administratie";

$L['plu_status'] = 'Status';
$L['plu_item'] = 'Abonnement';
$L['plu_since'] = 'Sinds';
$L['plu_notified'] = 'Laatste notificatie';
$L['plu_never'] = 'nooit';
$L['plu_notifyby'] = 'Notificeer via';
$L['plu_pm'] = 'Prive Bericht';
$L['plu_email'] = 'Email';
$L['plu_back'] = 'Terug';

$L['plu_explain'] = 'Je bent momenteel geabonneerd op de volgende topics:';

$L['plu_maxreached'] = '<strong>Je hebt het maximale aantal abonnementen bereikt.</strong>';

$L['plu_state_idle'] = "Non-actief (geen nieuwe posts)";
$L['plu_state_notifiedemail'] = "Genotificeerd via email";
$L['plu_state_notifiedpm'] = "Genotificeerd via PM";

$L['plu_monitored'][0] = 'Je bent niet geabonneerd op dit topic';
$L['plu_monitored'][1] = 'Je bent geabonneerd op dit topic via email';
$L['plu_monitored'][2] = 'Je bent geabonneerd op dit topic via PM';

$L['plu_monitor'][0] = 'Stop het abonnement op dit topic';
$L['plu_monitor'][1] = 'Abonneer op dit topic via email';
$L['plu_monitor'][2] = 'Abonneer op dit topic via PM';
$L['plu_monitor'][8] = 'Bekijk al je abonnementen';
$L['plu_monitor'][9] = 'Kies een optie...';

$L['plu_notifyemailtitle'] = "Reactie op topic: ";
$L['plu_notifyemail'] = "Hoi %1\$s,\n\nJe ontvangt deze email omdat %4\$s heeft gereageerd op het topic genaamd '%3\$s'.\nKlik hier om het bericht te lezen: %5\$s\n\nJe kunt je abonnementen wijzigen op: %8\$s\n\nHet volgende bericht is zojuist geplaatst:\n-------------------------\n%9\$s\n-------------------------";

$L['plu_notifypmtitle'] = "Reactie op topic";
$L['plu_notifypm'] = "Hoi %1\$s,\n\nJe ontvangt deze PM omdat %4\$s heeft gereageerd op het topic genaamd '%3\$s'.\nKlik hier om het bericht te lezen: %5\$s\nAls je het abonnement op dit topic wilt stoppen, klik hier: %6\$s\nAls je AL je abonnementen wilt stoppen, klik hier: %7\$s";

$L['plu_stats_topics'] = 'Totaal aantal abonnementen';
$L['plu_stats_topics_bypm'] = 'Abonnementen via PM';
$L['plu_stats_topics_byem'] = 'Abonnementen via email';
$L['plu_stats_users'] = 'Gebruikers met minimaal een abonnement';

$L['plu_stats_pm'] = 'Totaal aantal Prive Berichten verzonden';
$L['plu_stats_em'] = 'Totaal aantal emails verzonden';

?>
