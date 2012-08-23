<?php

/* ====================
[BEGIN_COT_EXT]
Hooks=forums.posts.main
[END_COT_EXT]
==================== */

defined('COT_CODE') or die("Wrong URL.");

if (cot_auth('notifier', 'any', 'R'))
{
	require_once cot_incfile('notifier', 'module');
	cot_notifier_read('forumtopic', $q);
}

?>