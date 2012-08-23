<?php

/* ====================
[BEGIN_COT_EXT]
Hooks=forums.posts.tags
[END_COT_EXT]
==================== */

defined('COT_CODE') or die("Wrong URL.");

list($notifier_auth_read, $notifier_auth_write) = cot_auth('notifier', 'any', 'RW');

if ($notifier_auth_read)
{
	require_once cot_incfile('notifier', 'module');
	
	$notifier_auth_write && cot_notifier_set_state('forumtopic', $q, 'active');
	
	$t->assign(cot_notifier_tags('forumtopic', $q, $rowt['ft_title']));
}

?>