<?php

/* ====================
[BEGIN_COT_EXT]
Hooks=notifier.config
[END_COT_EXT]
==================== */

defined('COT_CODE') or die("Wrong URL.");

if (cot_module_active('page'))
{
	require_once cot_incfile('page', 'module');
	$areas['pagecategory'] = array(
		'description' => $L['trigger_pagecategory'],
		'frequency' => 'all',
		'urlparams' => array('page', 'c={$itemid}'),
		'records' => array(
			'table' => $db_pages,
			'itemid' => 'page_cat',
			'ownerid' => 'page_ownerid',
			'timestamp' => 'page_date',
			'message' => 'page_text'
		),
	);
}

?>