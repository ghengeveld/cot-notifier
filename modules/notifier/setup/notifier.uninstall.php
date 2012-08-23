<?php

defined('COT_CODE') or die('Wrong URL');

global $db_users;

cot_extrafield_remove($db_users, 'autosubscribe');
cot_extrafield_remove($db_users, 'htmlemail');

?>