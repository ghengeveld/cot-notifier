<?php

defined('COT_CODE') or die('Wrong URL');

$L['Notifier'] = 'Notifier';
$L['Subscriptions'] = 'Subscriptions';

$L['Subscribe'] = 'Subscribe';
$L['Unsubscribe'] = 'Unsubscribe';
$L['ViewMessage'] = 'View the message';
$L['EmailSettings'] = 'Email settings';
$L['ViewSubscriptions'] = 'View subscriptions';

$L['Type'] = 'Type';
$L['SubscribedSince'] = 'Subscribed since';
$L['LastNotification'] = 'Last notification';
$L['Never'] = 'Never';

$L['YourSubscriptions'] = 'Your subscriptions';
$L['NoSubscriptions'] = 'You have no subscriptions.';

$L['SubscribeSuccess'] = 'You have succesfully subscribed.';
$L['SubscribeFailed'] = 'An error occured while attempting to subscribe.';
$L['UnsubscribeSuccess'] = 'You have succesfully unsubscribed.';
$L['UnsubscribeFailed'] = 'An error occured while attempting to subscribe.';
$L['PauseFailed'] = 'Pause subscription failed.';
$L['UnpauseFailed'] = 'Unpause subscription failed.';

$L['SubscriptionSettings'] = 'Subscription settings';
$L['NotifyMe'] = 'Notify me...';
$L['Frequency'] = 'Frequency';
$L['AutoSubscribe'] = 'Subscribe automatically to my own content';
$L['HTMLEmail'] = 'Send emails in HTML format';

$L['trigger_forumtopic'] = 'When someone posts in a subscribed topic';
$L['trigger_pagecategory'] = 'When a new page is added to a subscribed category';
$L['trigger_comments'] = 'When someone posts a comment to a subscribed thread';

$L['freq_first'] = 'Once, until I read or reply';
$L['freq_all'] = 'After every post or update';
$L['freq_daily'] = 'In a daily digest';
$L['freq_weekly'] = 'In a weekly digest';
$L['freq_monthly'] = 'In a monthly digest';
$L['freq_never'] = 'Never';

$L['EmailGreeting'] = 'Hi {$user_name},';
$L['EmailDesc'] = '{$poster_name} posted a new message on "{$sub_desc}":';

$L['EmailSubject'] = 'Re: {$sub_desc_short}';
$L['EmailTemplate'] = "{$L['EmailGreeting']}

{$L['EmailDesc']}

{\$quote}

{$L['ViewMessage']}: {\$url_view}
{$L['Unsubscribe']}: {\$url_unsubscribe}
{$L['EmailSettings']}: {\$url_notifier}

---
{$cfg['maintitle']} - {$cfg['subtitle']}";

$Ls['subscriptions'] = array('subscriptions', 'subscription');
$Ls['subscribers'] = array('subscribers', 'subscriber');

?>