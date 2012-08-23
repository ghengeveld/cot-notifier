<?php

/* ====================
[BEGIN_COT_EXT]
Hooks=notifier.config
[END_COT_EXT]
==================== */

defined('COT_CODE') or die("Wrong URL.");

if (cot_module_active('forums'))
{
	require_once cot_incfile('forums', 'module');
	
	$areas['forumtopic'] = array(
		'description' => $L['trigger_forumtopic'],
		'frequency' => 'first',
		'urlparams' => array('forums', 'm=posts&q={$itemid}&n=last', '#bottom'),
		'records' => array(
			'table' => $db_forum_posts,
			'itemid' => 'fp_topicid',
			'ownerid' => 'fp_posterid',
			'timestamp' => 'fp_creation',
			'message' => 'fp_text'
		),
	);
}

?>