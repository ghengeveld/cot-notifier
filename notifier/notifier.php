<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
http://www.neocrome.net

[BEGIN_SED]
File=plugins/notifier/notifier.php
Version=121
Updated=2007-jul-30
Type=Plugin
Author=Neocrome
Description=Notifier plugin ported to Seditio by Koradhil
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=notifier
Part=main
File=notifier
Hooks=standalone
Tags=
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]

==================== */

if ( !defined('SED_CODE') || !defined('SED_PLUG') ) { die("Wrong URL."); }

$a = sed_import('a','G','ALP');
$m = sed_import('m','G','ALP');
$f = sed_import('f','G','ALP');
$id = sed_import('id','G','STX');
$by = sed_import('by','G','ALP');
$userid = sed_import('userid','G','INT');
$b = sed_import('b','G','STX');

require('plugins/notifier/notifier.config.php');

if ($userid==0)
	{ $userid = $usr['id']; }

switch($m)
	{

	/* ============= */
	case 'admin':
	/* ============= */

	sed_block(sed_auth('plug', 'notifier', 'A'));

	$plugin_title = "<a href=\"plug.php?e=notifier&amp;m=admin\">".$L['plu_title_adm']."</a>";

	switch ($a)
		{
		/* =============== */
		case 'delete':
		/* =============== */

		sed_check_xg();
		$sql = sed_sql_query("DELETE FROM $db_notifier WHERE not_id='$id'");
		header("Location: plug.php?e=notifier&m=admin");
		exit;

		/* =============== */
		case 'update':
		/* =============== */

		sed_check_xg();

		sed_die();

		break;

		default:

		$sql = sed_sql_query("SELECT COUNT(DISTINCT not_userid) FROM $db_notifier");
		$not_users = sed_sql_result($sql, 0, 0);
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_notifier WHERE not_bypm=0");
		$not_byem = sed_sql_result($sql, 0, "COUNT(*)");
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_notifier WHERE not_bypm=1");
		$not_bypm = sed_sql_result($sql, 0, "COUNT(*)");
		$not_byem_perc = floor(100*($not_byem/($not_bypm+$not_byem)));
		$not_bypm_perc = 100-$not_byem_perc ;

		$plugin_body .= "<p>".$L['plu_stats_topics']." : ".count($db_notifier)."<br />";
		$plugin_body .= $L['plu_stats_topics_bypm']." : ".$not_bypm." (".$not_bypm_perc."%)<br />";
		$plugin_body .= $L['plu_stats_topics_byem']." : ".$not_byem." (".$not_byem_perc."%)<br />";
		$plugin_body .= $L['plu_stats_users']." : ".$not_users."</p>";
		$plugin_body .= "<p>".$L['plu_stats_pm']." : ".sed_stat_get('notifier_pm')."<br />";
		$plugin_body .= $L['plu_stats_em']." : ".sed_stat_get('notifier_em')."</p>";

		break;
		}

	break;


	/* ============= */
	default:
	/* ============= */

	sed_block(sed_auth('plug', 'notifier', 'W'));

	$plugin_title = "<a href=\"abonnementen\">".$L['plu_title']."</a>";

	if ($a=='add')
		{
		$it_code = strtolower(substr($id, 0, 1));
		$it_id = substr($id, 1, 15);

		if	( !( $it_code=='f' || $it_code=='p' || $it_code=='e' || $it_code=='l') || !($by=='pm' || $by=='em'))
			{ sed_die(); }

		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_notifier WHERE not_userid='$userid'");
		$tot_items = sed_sql_result($sql, 0, "COUNT(*)");
		if ($tot_items>=$cfg['plugin']['notifier']['maxitems'])
			{
			$disp_msg = $L['plu_maxreached'];
			}
		else
			{
			$by = ($by=='pm') ? 1 : 0;
			switch ($it_code)
				{
				case 'f':

				$sql = sed_sql_query("SELECT t.ft_title, s.fs_title FROM $db_forum_topics AS t,$db_forum_sections AS s
				WHERE t.ft_sectionid=s.fs_id AND t.ft_movedto=0 AND ft_id='".$it_id."' LIMIT 1");

				if ($row = mysql_fetch_array($sql))
					{ $notdesc = addslashes($row['ft_title']); }
				else
					{ sed_die(); }
				break;

				case 'p':
				if (!empty($sed_cat[$it_id]['title']))
					{ $notdesc = sed_build_catpath($it_id, "%2\$s"); }
					else
					{ sed_die(); }

				break;

				case 'l':

				if (!empty($sed_cat[$it_id]['title']))
					{ $notdesc = sed_build_catpath($it_id, "%2\$s"); }
					else
					{ sed_die(); }

				break;
	/*
			case 'n':

			break;

			case 'e':

			break;
	*/
				default:
				sed_die();
				break;
				}


			$sql = sed_sql_query("DELETE FROM $db_notifier WHERE not_userid='$userid' AND not_item='$id'");
			$sql = sed_sql_query("INSERT into $db_notifier (not_userid, not_state, not_bypm,  not_date, not_notified, not_item, not_desc) VALUES ('$userid', '0', '$by', '".$sys['now_offset']."', '0', '$id', '$notdesc' )");
			}
		}
	elseif ($a=='del')
		{
		$sql = sed_sql_query("DELETE FROM $db_notifier WHERE not_userid='$userid' AND not_id='$id'");
		}
	elseif ($a=='delall')
		{
		$sql = sed_sql_query("DELETE FROM $db_notifier WHERE not_userid='$userid'");
		}
	elseif ($a=='edit')
		{
		$by = ($by=='pm') ? 1 : 0;
		$sql = sed_sql_query("UPDATE $db_notifier SET not_bypm='$by' WHERE not_userid='$userid' AND not_id='$id'");
		}

	$sql = sed_sql_query("SELECT * FROM $db_notifier WHERE not_userid='$userid' ORDER BY not_item ASC");

	$plugin_body .= "<a href=\"javascript:history.go(-1)\">".$L['plu_back']."</a> &nbsp; <a href=\"plug.php?e=notifier\">".$L['Refresh']."</a>";
	$plugin_body .= ($usr['level']>=$cfg['plugin']['notifier']['modlevel']) ? " &nbsp; <a href=\"plug.php?e=notifier&amp;m=admin\">".$L['Administration']."</a>" : '';

	$plugin_body .= (!empty($disp_msg)) ? "<div style=\"padding:16px; text-align:center\">".$disp_msg."</div>" : '';
	$plugin_body .= "<p>".$L['plu_explain']."</p>";

	$plugin_body .= "<table class=\"cells\">";
	$plugin_body .= "<tr><td style=\"width:40px;\">".$L['Delete']."</td>";
	$plugin_body .= "<td>".$L['Topic']."</td>";
	$plugin_body .= "<td style=\"width:72px; text-align:center;\">".$L['plu_since']."</td>";
	$plugin_body .= "<td style=\"width:72px; text-align:center;\">".$L['plu_notified']."</td>";
	$plugin_body .= "<td style=\"width:112px; text-align:center;\">".$L['plu_notifyby']."</td>";
	$plugin_body .= "</tr>";

	while ($row = mysql_fetch_array($sql))
		{
		$it_code = strtolower(substr($row['not_item'], 0, 1));
		$it_id = substr($row['not_item'], 1, 15);
		$row['not_desc'] = sed_cc($row['not_desc']);

		switch ($it_code)
			{
			case 'f':
			$it_url = "forums.php?m=posts&amp;q=".$it_id;
			$it_desc = $row['not_desc'];
			break;
/*
			case 'p':
			$it_url = "list.php?c=".$it_id;
			$it_desc = $nottype['p']. " : ".$row['not_desc'];
			break;

			case 'l':
			$it_url = "links.php?c=".$it_id;
			$it_desc = $nottype['l']. " : ".$row['not_desc'];
			break;

			case 'n':
			$it_url = "".$it_id;
			$it_desc = $nottype['n']. " : ".$row['not_desc'];
			break;

			case 'e':
			$it_url = "".$it_id;
			$it_desc = $nottype['e']. " : ".$row['not_desc'];
			break;
	*/
			default:
			$it_url = "plug.php?e=notifier";
			$it_desc = "?";
			break;
			}

		$plugin_body .= "<tr>";
		$plugin_body .= "<td>[<a href=\"plug.php?e=notifier&amp;a=del&amp;id=".$row['not_id']."&amp;".sed_xg()."\">x</a>]</td>";
		$plugin_body .= "<td style=\"background-color:#".$bgcolor[$row['not_state']]."!important;\"><a href=\"".$it_url."\"> <img src=\"".$noticon[$it_code]."\" alt=\"\"> ".$it_desc."</a> </td>";
		$plugin_body .= "<td style=\"text-align:center;\">".date($cfg['formatyearmonthday'], $row['not_date'] + $usr['timezone'] * 3600)."</td>";
		$plugin_body .= "<td style=\"text-align:center;\">";
		$plugin_body .= (!$row['not_notified']) ? $L['plu_never'] : date($cfg['formatyearmonthday'], $row['not_notified'] + $usr['timezone'] * 3600)."</td>";
		$plugin_body .= "<td style=\"text-align:center;\">";

		$selected0 = ($row['not_bypm']==0) ? " selected=\"selected\"" : '';
		$selected1 = ($row['not_bypm']==1) ? " selected=\"selected\"" : '';
		$plugin_body .= "<form style=\"margin:0px;\" id=\"notifyby\">";
		$plugin_body .= "<select name=\"nb\" size=\"1\" onchange=\"redirect(this)\">";
		$plugin_body .= "<option value=\"plug.php?e=notifier&amp;a=edit&amp;id=".$row['not_id']."&amp;by=em\"".$selected0.">".$notby[0]."</option>";
		$plugin_body .= "<option value=\"plug.php?e=notifier&amp;a=edit&amp;id=".$row['not_id']."&amp;by=pm\"".$selected1.">".$notby[1]."</option>";
		$plugin_body .= "</select></form>";

		$plugin_body .= "</td>";
		$plugin_body .= "</tr>";
		}

	$plugin_body .= (sed_sql_numrows($sql)==0) ? "<tr><td colspan=\"5\">".$L['None']."</td></tr>" : '';
	$plugin_body .= "</table>";

	$plugin_body .= "<table class=\"cells\" style=\"width:400px; padding:24px 12px 12px 12px;\">
			<tr>
			<td style=\"background-color:#".$bgcolor[0]."; width:33%;\">&nbsp;".$notstate[0]."&nbsp;</td>
			<td style=\"background-color:#".$bgcolor[1]."; width:34%;\">&nbsp;".$notstate[1]."&nbsp;</td>
			<td style=\"background-color:#".$bgcolor[2]."; width:33%;\">&nbsp;".$notstate[2]."&nbsp;</td>
			</tr>
		</table>";

	break;
	}

?>
