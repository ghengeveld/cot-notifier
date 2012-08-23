<?php

/* ====================
[BEGIN_COT_EXT]
Hooks=forums.posts.newpost.done
[END_COT_EXT]
==================== */

defined('COT_CODE') or die("Wrong URL.");

if (cot_auth('notifier', 'any', 'R'))
{
	require_once cot_incfile('notifier', 'module');
	cot_notifier_trigger('forumtopic', $q, $usr['id'], cot_import('rmsgtext', 'P', 'HTM'));
}

?>