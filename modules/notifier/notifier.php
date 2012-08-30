<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=module
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

define('COT_NOTIFIER', true);
$env['location'] = 'notifier';

require_once cot_incfile('forms');
require_once cot_incfile('notifier', 'module');

cot_block($usr['auth_read'] && $areas);

switch ($a)
{
	case 'subscribe':
		$area = cot_import('area', 'G', 'ALP');
		$item_id = cot_import('item', 'G', 'INT');
		$desc = cot_import('desc', 'G', 'TXT');
		$redirect = cot_import('redirect', 'G', 'TXT');
		$done = cot_notifier_subscribe($area, $item_id, $desc);
		if ($done)
		{
			cot_message('SubscribeSuccess');
		}
		else
		{
			cot_error('SubscribeFailed');
		}
		if ($redirect)
		{
			cot_redirect(base64_decode($redirect));
		}
		cot_redirect(cot_url('notifier', '', '', true));

	case 'unsubscribe':
		$sub_id = cot_import('id', 'G', 'INT');
		$redirect = cot_import('redirect', 'G', 'TXT');
		$done = cot_notifier_unsubscribe($sub_id);
		if ($done)
		{
			cot_message('UnsubscribeSuccess');
		}
		else
		{
			cot_error('UnsubscribeFailed');
		}
		if ($redirect)
		{
			cot_redirect(base64_decode($redirect));
		}
		cot_redirect(cot_url('notifier', '', '', true));
		break;

	case 'pause':
		$area = cot_import('area', 'G', 'ALP');
		$item_id = cot_import('item', 'G', 'INT');
		$done = cot_notifier_set_state($area, $item_id, 'paused');
		if (!$done)
		{
			cot_error('PauseFailed');
		}
		cot_redirect(cot_url('notifier', '', '', true));
		
	case 'unpause':
		$area = cot_import('area', 'G', 'ALP');
		$item_id = cot_import('item', 'G', 'INT');
		$done = cot_notifier_set_state($area, $item_id, 'active');
		if (!$done)
		{
			cot_error('UnpauseFailed');
		}
		cot_redirect(cot_url('notifier', '', '', true));
		
	case 'settings':
		$frequencies = cot_import('frequencies', 'P', 'ARR');
		foreach ($frequencies as $area => $frequency)
		{
			$frequency = cot_import($frequency, 'D', 'ALP');
			cot_notifier_frequency_set($area, $frequency);
		}
		cot_notifier_preference_set('autosubscribe', cot_import('autosubscribe', 'P', 'BOL'));
		cot_notifier_preference_set('htmlemail', cot_import('htmlemail', 'P', 'BOL'));
		
		cot_redirect(cot_url('notifier', '', '', true));
}

$subscriptions = cot_notifier_list_subscriptions();

$title_params = array(
	'NOTIFIER' => $L['Notifier'],
	'SUBSCRIPTIONS' => $L['Subscriptions'],
	'COUNT' => count($subscriptions)
);
$out['subtitle'] = cot_title('{SUBSCRIPTIONS} ({COUNT})', $title_params);

require_once $cfg['system_dir'] . '/header.php';

$t = new XTemplate(cot_tplfile('notifier'));

if ($subscriptions)
{
	foreach ($subscriptions as $sub)
	{
		$t->assign(array(
			'SUB_ID' => $sub['sub_id'],
			'SUB_AREA' => $sub['sub_area'],
			'SUB_ITEMID' => $sub['sub_itemid'],
			'SUB_DESC' => $sub['sub_desc'],
			'SUB_STATE' => $sub['sub_state'],
			'SUB_CREATED' => $sub['sub_created'],
			'SUB_UPDATED' => $sub['sub_updated'],
			'SUB_LASTSENT' => $sub['sub_lastsent']
		));
		$t->parse('MAIN.SUBSCRIPTIONS.ROW');
	}
	$t->parse('MAIN.SUBSCRIPTIONS');
}
else
{
	$t->parse('MAIN.NOSUBSCRIPTIONS');
}

foreach ($areas as $code => $area)
{
	$t->assign(array(
		'SETTINGS_FORM_AREA_CODE' => $code,
		'SETTINGS_FORM_AREA_DESC' => $area['description'],
		'SETTINGS_FORM_AREA_FREQUENCY' => cot_selectbox(cot_notifier_frequency_get($code), "frequencies[$code]", $freq_codes, $freq_titles, false)
	));
	$t->parse('MAIN.AREA');
}
$t->assign(array(
	'SETTINGS_FORM_URL' => cot_url('notifier', 'a=settings'),
	'SETTINGS_FORM_AUTOSUBSCRIBE' => cot_checkbox(cot_notifier_preference_get('autosubscribe'), "autosubscribe", $L['AutoSubscribe']),
	'SETTINGS_FORM_HTMLEMAIL' => cot_checkbox(cot_notifier_preference_get('htmlemail'), "htmlemail", $L['HTMLEmail'])
));

$t->parse('MAIN');
$t->out('MAIN');

require_once $cfg['system_dir'] . '/footer.php';

?>