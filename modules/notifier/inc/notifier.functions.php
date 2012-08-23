<?php

defined('COT_CODE') or die('Wrong URL');

global $db_x, $db_subscriptions, $db_settings, $areas, $freq_codes, $freq_titles, $usr;
if (!$db_subscriptions) $db_subscriptions = $db_x.'notifier_subscriptions';
if (!$db_settings) $db_settings = $db_x.'notifier_settings';

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('notifier', 'any');

require_once cot_langfile('notifier', 'module');

$freq_codes = array('first', 'all', 'daily', 'weekly', 'monthly', 'never');
$freq_titles = array($L['freq_first'], $L['freq_all'], $L['freq_daily'], $L['freq_weekly'], $L['freq_monthly'], $L['freq_never']);

$areas = array();

/* === Hook === */
foreach (cot_getextplugins('notifier.config') as $pl)
{
	include $pl;
}
/* ===== */

/**
 * Subscribes to an item.
 *
 * @global CotDB $db
 * @param string $area Area code (e.g. 'forumtopic')
 * @param string $item_id Item identifier (e.g. value of structure_code or fp_topicid)
 * @param string $item_desc Item description (e.g. topic title or category title)
 * @param int $user_id User ID, defaults to current user
 * @return bool
 */
function cot_notifier_subscribe($area, $item_id, $item_desc, $user_id = null)
{
	global $db, $db_subscriptions, $sys, $usr;
	if (!$area || !$item_id) return false;
	if (!$user_id) $user_id = $usr['id'];
	
	/* === Hook === */
	foreach (cot_getextplugins('notifier.subscribe') as $pl)
	{
		include $pl;
	}
	/* ===== */

	cot_block($usr['auth_write'] && ($user_id == $usr['id'] || $usr['isadmin']));
	
	return (bool)$db->insert($db_subscriptions, array(
		'sub_userid' => $user_id,
		'sub_area' => $area,
		'sub_itemid' => $item_id,
		'sub_desc' => $item_desc,
		'sub_created' => $sys['now']
	));
}

/**
 * Removes a subscription.
 *
 * @global CotDB $db
 * @param int $sub_id Subscription ID
 * @return bool
 */
function cot_notifier_unsubscribe($sub_id)
{
	global $db, $db_subscriptions, $usr;
	if (!is_int($sub_id)) return;
	$anduser = cot_auth('notifier', 'any', 'A') ? '' : " AND sub_userid = {$usr['id']}" ;
	
	/* === Hook === */
	foreach (cot_getextplugins('notifier.unsubscribe') as $pl)
	{
		include $pl;
	}
	/* ===== */
	
	return (bool)$db->delete($db_subscriptions, "sub_id = $sub_id".$anduser);
}

/**
 * Removes all subscriptions for a specific user.
 *
 * @global CotDB $db
 * @param int $user_id User ID, defaults to current user
 * @return bool
 */
function cot_notifier_unsubscribe_all($user_id = null)
{
	global $db, $db_subscriptions, $usr;
	if (!is_int($user_id)) $user_id = $usr['id'];
	
	/* === Hook === */
	foreach (cot_getextplugins('notifier.unsubscribe_all') as $pl)
	{
		include $pl;
	}
	/* ===== */
	
	cot_block($user_id == $usr['id'] || $usr['isadmin']);
	
	return (bool)$db->delete($db_subscriptions, "sub_userid = $user_id");
}

/**
 * Return subscription details for a specific item and user, or FALSE if no
 * subsciption was found.
 * 
 * @global CotDB $db
 * @param string $area Area code
 * @param string $item_id Item identifier
 * @param int $user_id User ID, defaults to current user
 * @return array Associative array or FALSE if not found
 */
function cot_notifier_check($area, $item_id, $user_id = null)
{
	global $db, $db_subscriptions, $usr;
	if (!$area || !$item_id) return false;
	if (!is_int($user_id)) $user_id = $usr['id'];
	
	/* === Hook === */
	foreach (cot_getextplugins('notifier.check') as $pl)
	{
		include $pl;
	}
	/* ===== */
	
	cot_block($user_id == $usr['id'] || $usr['isadmin']);
	
	return $db->query("
		SELECT *
		FROM $db_subscriptions
		WHERE sub_userid = ?
		AND sub_area = ?
		AND sub_itemid = ?
		LIMIT 1
	", array($user_id, $area, $item_id))->fetch();
}

/**
 * Returns the number of subscriptions for a specific user.
 * 
 * @global CotDB $db
 * @param int $user_id User ID, defaults to current user
 * @return int
 */
function cot_notifier_count_subscriptions($user_id = null)
{
	global $db, $db_subscriptions, $usr;
	if (!is_int($user_id)) $user_id = $usr['id'];
	
	/* === Hook === */
	foreach (cot_getextplugins('notifier.subscriptions.count') as $pl)
	{
		include $pl;
	}
	/* ===== */
	
	cot_block($user_id == $usr['id'] || $usr['isadmin']);
	
	return (int)$db->query("
		SELECT COUNT(*)
		FROM $db_subscriptions
		WHERE sub_userid = $user_id
	")->fetchColumn();
}

/**
 * Returns the number of subscribers to a certain item.
 * 
 * @global CotDB $db
 * @param string $area Area code
 * @param string $item_id Item identifier
 * @return int
 */
function cot_notifier_count_subscribers($area, $item_id)
{
	global $db, $db_subscriptions;
	if (!$area || !$item_id) return;
	
	/* === Hook === */
	foreach (cot_getextplugins('notifier.subscribers.count') as $pl)
	{
		include $pl;
	}
	/* ===== */
	
	return (int)$db->query("
		SELECT COUNT(*)
		FROM $db_subscriptions
		WHERE sub_area = ?
		AND sub_itemid = ?
	", array($area, $item_id))->fetchColumn();
}

/**
 * Returns list of subscriptions for a specific user.
 * 
 * @global CotDB $db
 * @param int $user_id User ID, defaults to current user
 * @return array Results from $db_subscriptions
 */
function cot_notifier_list_subscriptions($user_id = null)
{
	global $db, $db_subscriptions, $usr;
	if (!is_int($user_id)) $user_id = $usr['id'];
	
	/* === Hook === */
	foreach (cot_getextplugins('notifier.subscriptions.list') as $pl)
	{
		include $pl;
	}
	/* ===== */
	
	cot_block($user_id == $usr['id'] || $usr['isadmin']);
	
	return $db->query("
		SELECT *
		FROM $db_subscriptions
		WHERE sub_userid = $user_id
		ORDER BY sub_created DESC
	")->fetchAll();
}

/**
 * Returns list of subscribers to a certain item.
 * Authorization '1' is required for this action.
 * 
 * @global CotDB $db
 * @param string $area Area code
 * @param string $item_id Item identifier
 * @return array
 */
function cot_notifier_list_subscribers($area, $item_id)
{
	global $db, $db_subscriptions;
	if (!$area || !$item_id) return;
	
	/* === Hook === */
	foreach (cot_getextplugins('notifier.subscribers.list') as $pl)
	{
		include $pl;
	}
	/* ===== */
	
	cot_block(cot_auth('notifier', 'any', '1'));
	
	return $db->query("
		SELECT *
		FROM $db_subscriptions
		WHERE sub_area = ?
		AND sub_itemid = ?
		ORDER BY sub_created DESC
	", array($area, $item_id))->fetchAll();
}

/**
 * Set state on a subscription.
 * 
 * @global CotDB $db
 * @param string $area Area code
 * @param string $item_id Item identifier
 * @param string $state 'active', 'inactive' or 'paused'
 * @param int $user_id User ID, defaults to current user
 * @return bool
 */
function cot_notifier_set_state($area, $item_id, $state, $user_id = null)
{
	global $db, $db_subscriptions, $usr, $sys;
	if (!$area || !$item_id || !in_array($state, array('active','inactive','paused'))) return;
	if (!$user_id) $user_id = $usr['id'];
	
	/* === Hook === */
	foreach (cot_getextplugins('notifier.setstate') as $pl)
	{
		include $pl;
	}
	/* ===== */
	
	cot_block($user_id == $usr['id'] || $usr['isadmin']);

	return (bool)$db->update($db_subscriptions, array(
		'sub_state' => $state,
		'sub_updated' => $sys['now']
	), "sub_state != ? AND sub_area = ? AND sub_itemid = ? AND sub_userid = ?", array(
		$state, $area, $item_id, $user_id
	));
}

/**
 * Returns CoTemplate tags for notifier
 * 
 * @param string $area Area code
 * @param string $item_id Item identifier
 * @param string $desc Item description
 * @return array
 */
function cot_notifier_tags($area, $item_id, $desc)
{
	global $sys, $L;
	if (!$area || !$item_id) return;
	$subscription = cot_notifier_check($area, $item_id);
	
	$toggle_url = $subscription ? 
		cot_url('notifier', "a=unsubscribe&id={$subscription['sub_id']}&{$sys['url_redirect']}") : 
		cot_url('notifier', "a=subscribe&area=forumtopic&item=$item_id&desc=$desc&{$sys['url_redirect']}");
	$toggle_text = $subscription ? $L['Unsubscribe'] : $L['Subscribe'];
	
	/* === Hook === */
	foreach (cot_getextplugins('notifier.tags') as $pl)
	{
		include $pl;
	}
	/* ===== */
	
	return array(
		'NOTIFIER_SUBSCRIBED' => (bool)$subscription,
		'NOTIFIER_TOGGLE' => cot_rc_link($toggle_url, $toggle_text),
		'NOTIFIER_TOGGLE_URL' => $toggle_url,
		'NOTIFIER_TOGGLE_TEXT' => $toggle_text,
		'NOTIFIER_SUBSCRIPTIONS' => cot_rc_link(cot_url('notifier'), $L['ViewSubscriptions']),
		'NOTIFIER_SUBSCRIPTIONS_URL' => cot_url('notifier')
	);
}

/**
 * Re-activates a subscription
 * 
 * @global CotDB $db
 * @param string $area Area code
 * @param string $item_id Item identifier
 * @param int $user_id User ID, defaults to current user
 * @return bool
 */
function cot_notifier_read($area, $item_id, $user_id = null)
{
	global $db, $db_subscriptions, $sys, $usr;
	if (!$area || !$item_id) return;
	if (!$user_id) $user_id = $usr['id'];
	
	/* === Hook === */
	foreach (cot_getextplugins('notifier.read') as $pl)
	{
		include $pl;
	}
	/* ===== */
	
	cot_block($user_id == $usr['id'] || $usr['isadmin']);
	
	return (bool)$db->update($db_subscriptions, array(
		'sub_state' => 'active',
		'sub_updated' => $sys['now']
	), "sub_userid = ? AND sub_area = ? AND sub_itemid = ? AND sub_state = 'inactive'", array(
		$user_id, $area, $item_id
	));
}

/**
 * Determines whether an email should be sent, based on state, frequency and last sent date
 * 
 * @param string $area Area code
 * @param string $state Subscription state (first, all, daily, weekly or monthly)
 * @param int $lastnotification Timestamp of last notification
 * @param int $user_id User ID
 * @return boolean 
 */
function cot_notifier_sendable($area, $state, $lastnotification, $user_id)
{
	global $sys;
	$frequency = cot_notifier_frequency_get($area, $user_id);
	switch ($frequency)
	{
		case 'first':
			return ($state == 'active');
		case 'all':
			return true;
		case 'daily':
			return ($sys['now'] > $lastnotification + 86400);
		case 'weekly':
			return ($sys['now'] > $lastnotification + 604800);
		case 'monthly':
			return ($sys['now'] > $lastnotification + 2629743);
	}
	return false;
}

/**
 * Trigger an update on a certain item, sending out emails if necessary.
 * 
 * @global File_cache $cache
 * @global CotDB $db
 * @param string $area Area code
 * @param string $item_id Item identifier
 * @param int $posterid ID of user who triggered the update
 * @param string $message Message which the user posted, triggering the update
 */
function cot_notifier_trigger($area, $item_id, $posterid, $message)
{
	global $areas, $db, $db_subscriptions, $db_users, $sys;
	if (!$area || !$item_id || !$posterid) return;
	if (!array_key_exists($area, $areas) || !array_key_exists('urlparams', $areas[$area])) return;
	
	$urlparams = $areas[$area]['urlparams'];
	$urlparams[1] = cot_rc($urlparams[1], array('itemid' => $item_id));
	if (!isset($urlparams[2])) $urlparams[2] = '';
	
	/* === Hook === */
	foreach (cot_getextplugins('notifier.trigger.query') as $pl)
	{
		include $pl;
	}
	/* ===== */

	isset($subscriptions) || $subscriptions = $db->query("
		SELECT s.*,
			u.user_id,
			u.user_name,
			u.user_email,
			p.user_id AS poster_id,
			p.user_name AS poster_name
		FROM $db_subscriptions AS s
		INNER JOIN $db_users AS u
		ON u.user_id = s.sub_userid
		LEFT JOIN $db_users AS p
		ON p.user_id = ?
		WHERE s.sub_area = ?
		AND s.sub_itemid = ?
		AND s.sub_userid != ?
		AND s.sub_state != 'paused'
	", array($posterid, $area, $item_id, $posterid))->fetchAll();

	if ($subscriptions)
	{
		if (cot_plugin_active('htmlmail'))
		{
			require_once cot_incfile('htmlmail', 'plug');
			$htmlfile = cot_tplfile("notifier.email.$area");
			$cssfile = cot_cssfile("notifier.email.inline.$area");
			$extcssfile = cot_cssfile("notifier.email.external.$area");
			$template_html = cot_htmlmail_template($htmlfile, $cssfile, $extcssfile);
		}
		$template_plain = 'EmailTemplate';

		/* === Hook === */
		foreach (cot_getextplugins('notifier.trigger.template') as $pl)
		{
			include $pl;
		}
		/* ===== */
	
		/* === Hook - get === */
		$extp = cot_getextplugins('notifier.trigger.loop');
		/* ===== */

		foreach ($subscriptions as $subscription)
		{
			$usehtml = $template_html ? 
				(bool)cot_notifier_preference_get('htmlemail', $subscription['sub_userid']) : false;
			$template = $usehtml ? $template_html : $template_plain;
			
			$sendable = cot_notifier_sendable(
				$subscription['sub_area'],
				$subscription['sub_state'],
				$subscription['sub_lastsent'],
				$subscription['sub_userid']
			);
			
			/* === Hook - include === */
			foreach ($extp as $pl) include $pl;
			/* ===== */
			
			if (!$sendable) continue;

			$db->update($db_subscriptions, array(
				'sub_state' => 'inactive',
				'sub_updated' => $sys['now'],
				'sub_lastsent' => $sys['now']
			), "sub_id = {$subscription['sub_id']}");
			
			if (cot_notifier_mail_update($usehtml, $template, $subscription, trim(strip_tags($message)), $urlparams))
			{
				cot_stat_update("notifier");
			}
		}
	}
}

/**
 * Fetches data for a periodic digest for a certain area.
 * 
 * @global CotDB $db
 * @param string $area Area code
 * @param int $user_id User ID, defaults to current user
 * @return array List of items with columns 'itemid', 'message' and 'url'
 */
function cot_notifier_digest($area, $user_id = null)
{
	global $areas, $db, $db_subscriptions, $usr;
	if (!$user_id) $user_id = $usr['id'];
	if (!array_key_exists($area, $areas) || !array_key_exists('urlparams', $areas[$area]) || !array_key_exists('records', $areas[$area])) return;

	$table = $areas[$area]['records']['table'];
	$col_itemid = $areas[$area]['records']['itemid'];
	$col_message = $areas[$area]['records']['message'];
	$col_timestamp = $areas[$area]['records']['timestamp'];
	$col_ownerid = $areas[$area]['records']['ownerid'];
	$andowner = $col_ownerid ? " AND $col_ownerid != ".(int)$user_id : '';

	$items = $db->query("
		SELECT p.$col_itemid AS itemid, p.$col_message AS message
		FROM $db_subscriptions AS s
		INNER JOIN $table AS p
		ON p.$col_itemid = s.sub_itemid
		AND p.$col_timestamp > s.sub_updated
		AND p.$col_timestamp > s.sub_lastsent
		WHERE s.sub_area = '$area'
		AND s.sub_userid = $user_id
		$andowner
	")->fetchAll(PDO::FETCH_ASSOC);
	
	$urlparams = $areas[$area]['urlparams'];
	if (!isset($urlparams[2])) $urlparams[2] = '';
	
	foreach ($items as &$item)
	{
		$urlparams[1] = cot_rc($urlparams[1], array('itemid' => $item['itemid']));
		$item['url'] = cot_url($urlparams[0], $urlparams[1], $urlparams[2], true);
	}
	
	return $items;
}

/**
 * Prepares and sends notification email for a single message.
 *
 * @param bool $htmlmail Send HTML instead of plaintext
 * @param string $template Email template (HTML or plaintext)
 * @param array $data Associative array of details regarding the subscription
 * @param string $message Message which the user posted, triggering the update
 * @param string $urlparams Params for cot_url(), e.g. array('forums', 'm=posts&q=$q&n=last', '#bottom')
 * @return bool Return value of cot_mail()
 */
function cot_notifier_mail_update($htmlmail, $template, $data, $message, $urlparams)
{
	global $sys;

	$data['message'] = $message;
	$data['quote'] = '> '.utf8_wordwrap(cot_string_truncate($message, 250, false, false, '...'), 74, "\n> ");
	$data['url_view'] = COT_ABSOLUTE_URL . cot_url($urlparams[0], $urlparams[1], $urlparams[2], true);
	$data['url_unsubscribe'] = COT_ABSOLUTE_URL . cot_url('notifier', "a=unsubscribe&id={$data['sub_id']}", '', true);
	$data['url_notifier'] = COT_ABSOLUTE_URL . cot_url('notifier', '', '', true);
	$data['sub_desc_short'] = cot_string_truncate($data['sub_desc'], 50, false, false, '...');
	$data['sub_created_text'] = cot_date('datetime_text', $data['sub_created']);
	$data['sub_updated_text'] = cot_date('datetime_text', $data['sub_updated']);
	$data['sub_lastsent_text'] = cot_date('datetime_text');

	if (cot_plugin_active('htmlmail'))
	{
		require_once cot_incfile('htmlmail', 'plug');
	}

	/* === Hook === */
	foreach (cot_getextplugins('notifier.mail.update.compile') as $pl)
	{
		include $pl;
	}
	/* ===== */

	$subject = cot_rc('EmailSubject', $data);
	$body = $htmlmail ? cot_htmlmail_compile($template, array(
		'SUBJECT' => $subject,
		'GREETING' => nl2br(cot_rc('EmailGreeting', $data)),
		'DESC' => nl2br(cot_rc('EmailDesc', $data)),
		'MESSAGE' => $data['message'],
		'DATE_CREATED' => $data['sub_created'],
		'DATE_UPDATED' => $data['sub_updated'],
		'DATE_SENT' => $sys['now'],
		'URL_VIEW' => htmlentities($data['url_view']),
		'URL_UNSUBSCRIBE' => htmlentities($data['url_unsubscribe']),
		'URL_NOTIFIER' => htmlentities($data['url_notifier'])
	)) : cot_rc($template, $data);

	/* === Hook === */
	foreach (cot_getextplugins('notifier.mail.update.send') as $pl)
	{
		include $pl;
	}
	/* ===== */

	return cot_mail($data['user_email'], $subject, $body, '', true, null, $htmlmail);
}

/**
 * Prepares and sends notification email digest.
 *
 * @param bool $htmlmail Send HTML instead of plaintext
 * @param string $template Email template (HTML or plaintext)
 * @param array $data Associative array of details regarding the subscription
 * @param string $message Message which the user posted, triggering the update
 * @param string $urlparams Params for cot_url(), e.g. array('forums', 'm=posts&q=$q&n=last', '#bottom')
 * @return bool Return value of cot_mail()
 */
function cot_notifier_mail_digest($htmlmail, $template, $data)
{
	global $sys;

	$data['url_unsubscribe'] = COT_ABSOLUTE_URL . cot_url('notifier', "a=unsubscribe&id={$data['sub_id']}", '', true);
	$data['url_notifier'] = COT_ABSOLUTE_URL . cot_url('notifier', '', '', true);
	$data['sub_desc_short'] = cot_string_truncate($data['sub_desc'], 50, false, false, '...');
	$data['sub_created_text'] = cot_date('datetime_text', $data['sub_created']);
	$data['sub_updated_text'] = cot_date('datetime_text', $data['sub_updated']);
	$data['sub_lastsent_text'] = cot_date('datetime_text');

	if (cot_plugin_active('htmlmail'))
	{
		require_once cot_incfile('htmlmail', 'plug');
	}

	/* === Hook === */
	foreach (cot_getextplugins('notifier.mail.digest.compile') as $pl)
	{
		include $pl;
	}
	/* ===== */

	$subject = cot_rc('EmailSubject', $data);
	$body = $htmlmail ? cot_htmlmail_compile($template, array(
		'SUBJECT' => $subject,
		'GREETING' => nl2br(cot_rc('EmailGreeting', $data)),
		'DESC' => nl2br(cot_rc('EmailDesc', $data)),
		'MESSAGES' => $messages, // TODO fix this
		'DATE_CREATED' => $data['sub_created'],
		'DATE_UPDATED' => $data['sub_updated'],
		'DATE_SENT' => $sys['now'],
		'URL_VIEW' => htmlentities($data['url_view']),
		'URL_UNSUBSCRIBE' => htmlentities($data['url_unsubscribe']),
		'URL_NOTIFIER' => htmlentities($data['url_notifier'])
	)) : cot_rc($template, $data);

	/* === Hook === */
	foreach (cot_getextplugins('notifier.mail.digest.send') as $pl)
	{
		include $pl;
	}
	/* ===== */

	return cot_mail($data['user_email'], $subject, $body, '', true, null, $htmlmail);
}

/**
 * Returns subscription frequency setting
 *
 * @global CotDB $db
 * @param string $area Area code
 * @param int $user_id User ID, defaults to current user
 * @param bool $getdefault Return default setting if not found
 * @return string One of possible values:
 *  'first', 'all', 'daily', 'weekly', 'monthly', 'never'
 *  or NULL if not found.
 */
function cot_notifier_frequency_get($area, $user_id = null, $getdefault = true)
{
	global $cfg, $db, $db_settings, $usr;
	if (!$user_id) $user_id = $usr['id'];
	
	$frequency = $db->query("
		SELECT set_frequency FROM $db_settings
		WHERE set_userid = ? AND set_area = ?
	", array($user_id, $area))->fetchColumn();
	
	return (!$frequency && $getdefault) ? $cfg['notifier']["freq_$area"] : $frequency;
}

/**
 * Creates or updates subscription frequency setting
 *
 * @global CotDB $db
 * @param string $area Area code
 * @param string $frequency One of possible values:
 *  'first', 'all', 'daily', 'weekly', 'monthly', 'never'
 * @param int $user_id User ID, defaults to current user
 * @return bool 
 */
function cot_notifier_frequency_set($area, $frequency, $user_id = null)
{
	global $freq_codes, $db, $db_settings, $usr, $sys;
	if (!$user_id) $user_id = $usr['id'];
	if (!in_array($frequency, $freq_codes)) return false;
	
	if (cot_notifier_frequency_get($area, $user_id, false))
	{
		return (bool)$db->update($db_settings, array(
			'set_frequency' => $frequency,
			'set_updated' => $sys['now']
		), "set_userid = ? AND set_area = ? AND set_frequency != ?", array(
			$user_id, $area, $frequency
		));
	}
	else
	{
		return (bool)$db->insert($db_settings, array(
			'set_userid' => $user_id,
			'set_area' => $area,
			'set_frequency' => $frequency,
			'set_updated' => $sys['now']
		));
	}
}

/**
 * Fetches a user preference from users table
 *
 * @global CotDB $db
 * @param string $field Extrafield name
 * @param int $user_id User ID, defaults to current user
 * @return string 
 */
function cot_notifier_preference_get($field, $user_id = null)
{
	global $cot_extrafields, $db, $db_users, $usr;
	if (!$user_id) $user_id = $usr['id'];
	if (!array_key_exists($field, $cot_extrafields[$db_users])) return;
	
	$pref = $db->query("SELECT user_$field FROM $db_users WHERE user_id = ?", array($user_id))->fetchColumn();
	return is_null($pref) ? $cot_extrafields[$db_users][$field]['field_default'] : $pref;
}

/**
 * Sets a user preference stored in users table
 *
 * @global CotDB $db
 * @param string $field Extrafield name
 * @param mixed $value Value to set
 * @param int $user_id User ID, defaults to current user
 * @return bool
 */
function cot_notifier_preference_set($field, $value, $user_id = null)
{
	global $db, $db_users, $usr;
	if (is_null($value)) return;
	if (!$user_id) $user_id = $usr['id'];
	$field = cot_alphaonly($field);
	
	return (bool)$db->update($db_users, array("user_$field" => $value), "user_id = ?", array($user_id));
}

/**
 * Multibyte-safe wordwrap
 *
 * @param string $str The input string
 * @param int $width The number of characters at which the string will be wrapped.
 * @param string $break The line is broken using the optional break parameter.
 * @param bool $cut If the cut is set to TRUE, the string is always wrapped at 
 *  or before the specified width. So if you have a word that is larger than the
 *  given width, it is broken apart. (See second example).
 * @return string
 */
function utf8_wordwrap($str, $width = 75, $break = "\n", $cut = false) {
    if (!$cut) {
        $regexp = '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){'.$width.',}\b#U';
    } else {
        $regexp = '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){'.$width.'}#';
    }
    if (function_exists('mb_strlen')) {
        $str_len = mb_strlen($str,'UTF-8');
    } else {
        $str_len = preg_match_all('/[\x00-\x7F\xC0-\xFD]/', $str, $var_empty);
    }
    $while_what = ceil($str_len / $width);
    $i = 1;
    $return = '';
    while ($i < $while_what) {
        preg_match($regexp, $str,$matches);
        $string = $matches[0];
        $return .= trim($string).$break;
        $str = substr($str, strlen($string));
        $i++;
    }
    return $return.trim($str);
}

?>