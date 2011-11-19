<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
http://www.neocrome.net

[BEGIN_SED]
File=plugins/notifier/includes/notifier.functions.php
Version=121
Updated=2007-jul-30
Type=Plugin
Author=Neocrome
Description=Notifier plugin ported to Seditio by Koradhil
[END_SED]
==================== */

if ( !defined('SED_CODE') ) { die("Wrong URL."); }

/* ------------------- */

function sed_notifier_get_state($userid, $item)
	{
	global $db_notifier;

	$sqltmp = sed_sql_query("SELECT not_id, not_state, not_bypm FROM $db_notifier WHERE not_item='$item' AND not_userid='$userid' ");

	if($rowtmp = mysql_fetch_array($sqltmp))
		{
		if ($rowtmp['not_state']>0)
			{
			$sql = sed_sql_query("UPDATE $db_notifier SET not_state='0' WHERE not_userid='$userid' AND not_id='".$rowtmp['not_id']."'");
			}
		$state = $rowtmp['not_bypm']+1;
		}
	else
		{
		$state = 0;
		}
	return(array($rowtmp['not_id'], $state));
	}

/* ------------------- */

function sed_notifier_get_list($item)
	{
	global $db_notifier, $db_users, $usr;
	$sql = sed_sql_query("SELECT n.*, u.user_name, u.user_email, p.fp_id, p.fp_text
		FROM $db_notifier AS n, $db_users AS u, sed_forum_posts AS p
		WHERE n.not_item='$item'
		AND u.user_id=n.not_userid
		AND u.user_banexpire=0
		AND n.not_userid!=".$usr['id']."
		AND n.not_state=0
		AND p.fp_topicid=".substr($item, 1)."
		ORDER BY p.fp_creation DESC LIMIT 1");

	return ($sql);
	}

/* ------------------- */

function sed_notifier_send($type, $not_id, $email, $username, $userid, $section, $desc, $updatedby, $url_read, $post)
	{
	global $db_notifier, $db_pm, $cfg, $sys, $L;

	if ($type==1) // -------- By PM -----
			{
			$body = sprintf($L['plu_notifypm'],
				$username, //1
				$section, //2
				$desc, //3
				$updatedby, //4
				$cfg['mainurl']."/".$url_read, //5
				$cfg['mainurl']."/plug.php?e=notifier&a=del&id=".$not_id, //6
				$cfg['mainurl']."/plug.php?e=notifier&a=delall", //7
				$cfg['mainurl']."/abonnementen", //8
				$post //9
				);

			$sqltmp = sed_sql_query("INSERT into $db_pm
			(pm_state, pm_date, pm_fromuserid, pm_fromuser, pm_touserid, pm_title, pm_text)
			VALUES ('0', '".$sys['now_offset']."', '0', '".addslashes($cfg['maintitle'])."', '".$userid."',
			'".addslashes($L['plu_notifypmtitle'])."', '".addslashes($body)."')");

			sed_stat_inc("notifier_pm");
			$not_state = 2;
			}
	else // -------- By email -----
			{
			$subject = $L['plu_notifyemailtitle'].$desc;

			$body = sprintf($L['plu_notifyemail'],
				$username, //1
				$section, //2
				$desc, //3
				$updatedby, //4
				$cfg['mainurl']."/".$url_read, //5
				$cfg['mainurl']."/plug.php?e=notifier&a=del&id=".$not_id, //6
				$cfg['mainurl']."/plug.php?e=notifier&a=delall", //7
				$cfg['mainurl']."/abonnementen", //8
				$post //9
					);

			sed_mail ($email, $subject, $body);
			sed_stat_inc("notifier_em");
			$not_state = 1;
			}

	$sqltmp = sed_sql_query("UPDATE $db_notifier SET not_state='".$not_state."',
		not_notified=".$sys['now_offset']." WHERE not_userid='".$userid."'
		AND not_id='".$not_id."'");



	}

/* ------------------- */

function sed_notify_build_box($id, $state, $item)
	{
	global $L, $cfg;

	$res = $L['plu_monitored'][$state]." ";

	$res .= "<form style=\"margin:0px;\" id=\"monitoring\">";
	$res .= "<select name=\"nb\" size=\"1\" onchange=\"redirect(this)\">";
	$res .= "<option value=\"plug.php?e=notifier\" selected=\"selected\">".$L['plu_monitor'][9]."</option>";

	switch ($state)
		{
		case '1':

		$res .= "<option value=\"plug.php?e=notifier&amp;a=del&amp;id=".$id."\">".$L['plu_monitor'][0]."</option>";
		$res .= ($cfg['plugin']['notifier']['allowpms']) ? "<option value=\"plug.php?e=notifier&amp;a=add&amp;id=".$item."&amp;by=pm\">".$L['plu_monitor'][2]."</option>" : '';
		break;

		case '2':
		$res .= "<option value=\"plug.php?e=notifier&amp;a=del&amp;id=".$id."\">".$L['plu_monitor'][0]."</option>";
		$res .= ($cfg['plugin']['notifier']['allowemails']) ? "<option value=\"plug.php?e=notifier&amp;a=add&amp;id=".$item."&amp;by=em\">".$L['plu_monitor'][1]."</option>" : '';
		break;

		default:
		$res .= ($cfg['plugin']['notifier']['allowemails']) ? "<option value=\"plug.php?e=notifier&amp;a=add&amp;id=".$item."&amp;by=em\">".$L['plu_monitor'][1]."</option>" : '';
		$res .= ($cfg['plugin']['notifier']['allowpms']) ? "<option value=\"plug.php?e=notifier&amp;a=add&amp;id=".$item."&amp;by=pm\">".$L['plu_monitor'][2]."</option>" : '';
		break;
		}

	$res .= "<option value=\"plug.php?e=notifier\">".$L['plu_monitor'][8]."</option>";
	$res .= "</select></form>";
	return($res);
	}

?>
