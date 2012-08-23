<?php

defined('COT_CODE') or die('Wrong URL');

global $db_users;

cot_extrafield_add($db_users, 'autosubscribe', 'checkbox', '', '', 1);
cot_extrafield_add($db_users, 'htmlemail', 'checkbox', '', '', 1);

?>