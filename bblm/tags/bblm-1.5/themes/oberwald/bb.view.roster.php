<?php
/*
Template Name: Team Roster
*/
/*
*	Filename: bb.view.roster.php
*	Version: 1.3
*	Description: .Page template to display
*/
/* -- Change History --
20080622 - 1.0b - Initial creation of file.
20080623 - 1.0.1b - Fixed some validation errors
20080722 - 1.1b - changed MNG from 1 to Y, added some more number formattion
20080730 - 1.0 - bump to Version 1 for public release.
20090117 - 1.1 - Changed title tag to have roster name then hdwsbbl
			   - Added Injuries to the roster
			   - The Team Captain (if assigned) is highlighted.
20090331 - 1.2 - The Roster will display the team logo if present and the race logo if not
20090824 - 1.2.1 - re-arranged the HTML title tag as it began with arrows and was annoying me!
20100123 - 1.3 - Updated the prefix for the custom bb tables in the Database (tracker [225])
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php wp_title(); ?> - HDWSBBL </title>
<?php wp_head(); ?>
<style type="text/css">
html * {
	margin:0;
	padding:0;
}
body {
	font-family: 'Lucida Grande', Verdana, Arial, Sans-Serif;
	font-size: 0.8em;
}
h1, div, table, p {
	margin-bottom: 0.8em
}
h1 {
	font-size: 1.6em;
}
table {
	margin-left: auto;
	margin-right: auto;
	background-color: #fff;
	border: 1px solid #000;
	border-collapse: collapse;
}
table td {
	text-align: center;
	border-left-style: dotted;
	border-bottom-style: solid;
	border-color: #000000;
	border-width: 1px;
	padding: 0.3em;
}
table th {
	border-bottom: 4px solid #000;
	background-color: #548ac3;
	color: #fff;
	font-weight: bold;
	text-align: center;
}
table th.tbl_enchance, table th.tbl_title {
	border-left-style: dotted;
	border-bottom-style: solid;
	border-color: #000000;
	border-width: 1px;
}
.tbl_stat {
	width:25px;
}
.tbl_skills {
	width:300px;
	text-align:left;
	font-size: smaller;
}
.tbl_name {
	width:200px;
}
.tbl_value {
	width:50px;
	text-align:right;
}
#footer {
	color: #666;
}
a, a:link, a:visited {
	color: #3366FF;
	text-decoration: none;
}

a:hover, a:active {
	color: #D7651B;
	text-decoration: underline;
}
</style>
</head>
<body>

<div id="wrapper">
	<div id="pagecontent">
		<div id="maincontent">

	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>

<?
		$teaminfosql = 'SELECT T.*, J.tid AS teamid, R.r_name, R.r_rrcost, L.guid AS racelink, U.display_name, W.post_title AS stad, W.guid AS stadlink, H.guid AS TeamLink, H.post_title AS TeamName FROM '.$wpdb->prefix.'team T, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P, '.$wpdb->users.' U, '.$wpdb->prefix.'race R, '.$wpdb->prefix.'bb2wp K, '.$wpdb->posts.' L, '.$wpdb->prefix.'bb2wp Q, '.$wpdb->posts.' W, '.$wpdb->prefix.'bb2wp Y, '.$wpdb->posts.' H WHERE T.t_id = Y.tid AND Y.prefix = \'t_\' AND Y.pid = H.ID AND Y.tid = J.tid AND T.stad_id = Q.tid AND Q.prefix = \'stad_\' AND Q.pid = W.ID AND T.r_id = K.tid AND K.prefix = \'r_\' AND K.pid = L.ID AND T.ID = U.ID AND R.r_id = T.r_id AND T.t_id = J.tid AND J.prefix = \'roster\' AND J.pid = P.ID AND P.ID = '.$post->ID;
		if ($ti = $wpdb->get_row($teaminfosql)) {
				$tid = $ti->teamid;

			//determine Team Captain
			$teamcaptainsql = 'SELECT * FROM '.$wpdb->prefix.'team_captain WHERE tcap_status = 1 and t_id = '.$tid;
			if ($tcap = $wpdb->get_row($teamcaptainsql)) {
				$teamcap = $tcap->p_id;
			}
?>
		<h1>Roster for <a href="<?php print($ti->TeamLink); ?>" title="Read more about <?php print($ti->TeamName); ?>"><?php print($ti->TeamName); ?></a></h1>

<?
		}
?>
<table border="0">
 <tr>
  <th class="tbl_stat">No.</th>
  <th class="tbl_name">Player Name</th>
  <th class="tbl_pos">Position</th>
  <th class="tbl_stat">MA</th>
  <th class="tbl_stat">ST</th>
  <th class="tbl_stat">AG</th>
  <th class="tbl_stat">AV</th>
  <th class="tbl_skills">Skills / Injuries</th>
  <th class="tbl_stat">INJ</th>
  <th class="tbl_stat">COMP</th>
  <th class="tbl_stat">TD</th>
  <th class="tbl_stat">INT</th>
  <th class="tbl_stat">CAS</th>
  <th class="tbl_stat">MVP</th>
  <th class="tbl_stat">SPP</th>
  <th class="tbl_value">Value</th>
 </tr>
<?php
		$playersql = 'SELECT K.post_title, K.guid, L.pos_name, P.* FROM '.$wpdb->prefix.'player P, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' K, '.$wpdb->prefix.'position L WHERE P.p_id = J.tid AND J.prefix = \'p_\' AND J.pid = K.ID AND P.pos_id = L.pos_id AND P.p_status = 1 AND P.t_id = '.$tid.' ORDER BY P.p_num ASC';
		$pcount = 1;
		if ($players = $wpdb->get_results($playersql)) {
			foreach ($players as $pl) {
				while ($pcount < $pl->p_num) {
					//print a generic row
?>
 <tr>
  <td><?php print($pcount); ?></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td class="tbl_skills">&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
<?php
					$pcount++;

				}
				//checks to see the player belongs in this position
				if ($pcount == $pl->p_num) {

				$playerdetailssql = 'SELECT SUM(M.mp_td) AS PTD, SUM(M.mp_cas) AS PCAS, SUM(M.mp_comp) AS PCOMP, SUM(M.mp_int) AS PINT, SUM(M.mp_MVP) AS PMVP FROM '.$wpdb->prefix.'player P, '.$wpdb->prefix.'match_player M, '.$wpdb->prefix.'match N, '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' O WHERE M.m_id = N.m_id AND N.c_id = C.c_id AND C.c_counts = 1 AND C.c_show = 1 AND P.p_id = J.tid AND J.prefix = \'p_\' AND J.pid = O.ID AND M.p_id = P.p_id AND M.mp_spp > 0 AND P.p_id = '.$pl->p_id;
				$pd = $wpdb->get_row($playerdetailssql)
?>
<tr>
  <td><?php print($pcount); ?></td>
  <td><?php print("<a href=\"".$pl->guid."\" title=\"View more information about ".$pl->post_title."\">".$pl->post_title."</a>"); if ($teamcap == $pl->p_id) { print(" (C)");} ?></td>
  <td><?php print($pl->pos_name); ?></td>
  <td><?php print($pl->p_ma); ?></td>
  <td><?php print($pl->p_st); ?></td>
  <td><?php print($pl->p_ag); ?></td>
  <td><?php print($pl->p_av); ?></td>
  <td class="tbl_skills"><?php print($pl->p_skills); ?>
<?php
	if ("none" !== $pl->p_injuries) {
		print(", <em>".$pl->p_injuries."</em>");
	}
?></td>
<?php
	if ($pl->p_mng) {
		print("  <td>Y</td>\n");
	}
	else {
		print("  <td>&nbsp;</td>\n");
	}

	if ($pd->PCOMP == 0) {
		print("  <td>&nbsp;</td>\n");
	}
	else {
		print("  <td>".$pd->PCOMP."</td>\n");
	}
	if ($pd->PTD == 0) {
		print("  <td>&nbsp;</td>\n");
	}
	else {
		print("  <td>".$pd->PTD."</td>\n");
	}
	if ($pd->PINT == 0) {
		print("  <td>&nbsp;</td>\n");
	}
	else {
		print("  <td>".$pd->PINT."</td>\n");
	}
	if ($pd->PCAS == 0) {
		print("  <td>&nbsp;</td>\n");
	}
	else {
		print("  <td>".$pd->PCAS."</td>\n");
	}
	if ($pd->PMVP == 0) {
		print("  <td>&nbsp;</td>\n");
	}
	else {
		print("  <td>".$pd->PMVP."</td>\n");
	}
?>
  <td><?php print($pl->p_spp); ?></td>
  <td class="tbl_value"><?php print(number_format($pl->p_cost_ng)); ?>gp</td>
 </tr>
<?php
				}
				$pcount++;
			}
		//now the sql has been completed, we have to print emtpy lines to ensure that 16 places are displayed!
			while (17 > $pcount) {
				//print a generic row
?>
 <tr>
  <td><?php print($pcount); ?></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td class="tbl_skills">&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
<?php
				$pcount++;

				}
		}
?>
 <!-- End of player listing -->
 <tr>
<?php
	$filename = $_SERVER['DOCUMENT_ROOT']."/images/teams/".$ti->t_sname."_big.gif";
	if (file_exists($filename)) {
?>
  <td colspan="3" rowspan="7" class="tbl_logo"><img src="<?php print(get_option('home')); ?>/images/teams/<?php print($ti->t_sname); ?>_big.gif" alt="Team Logo" /></td>
<?php
	}
	else {
?>
  <td colspan="3" rowspan="7" class="tbl_logo"><img src="<?php print(get_option('home')); ?>/images/races/race<?php print($ti->r_id); ?>.gif" alt="<?php print($ti->r_name); ?> Logo" /></td>
<?php
	}
?>
  <th colspan="4" rowspan="2" class="tbl_title">Team Name:</th>
  <td rowspan="2"><a href="<?php print($ti->TeamLink); ?>" title="Read more about <?php print($ti->TeamName); ?>"><?php print($ti->TeamName); ?></a></td>
  <th colspan="3" class="tbl_title">Re-Rolls:</th>
  <td><?php print($ti->t_rr); ?></td>
  <th class="tbl_enchance">X</th>
  <th class="tbl_enchance" colspan="2"><?php print(number_format($ti->r_rrcost)); ?>gp</th>
  <td class="tbl_value"><?php print(number_format($ti->t_rr*$ti->r_rrcost)); ?>gp</td>
 </tr>
 <tr>
  <th colspan="3" class="tbl_title">Fan Factor:</th>
  <td><?php print($ti->t_ff); ?></td>
  <th class="tbl_enchance">X</th>
  <td colspan="2">10,000gp</td>
  <td class="tbl_value"><?php print(number_format($ti->t_ff*10000)); ?>gp</td>
 </tr>
 <tr>
  <th colspan="4" rowspan="2" class="tbl_title">Race:</th>
  <td rowspan="2"><a href="<?php print($ti->racelink); ?>" title="Read more about <?php print($ti->r_name); ?> teams"><?php print($ti->r_name); ?></a></td>
  <th colspan="3" class="tbl_title">Assistant Coaches:</th>
  <td><?php print($ti->t_ac); ?></td>
  <th class="tbl_enchance">X</th>
  <td colspan="2">10,000gp</td>
  <td class="tbl_value"><?php print(number_format($ti->t_ac*10000)); ?>gp</td>
 </tr>
 <tr>
  <th colspan="3" class="tbl_title">Cheerleaders:</th>
  <td><?php print($ti->t_cl); ?></td>
  <th class="tbl_enchance">X</th>
  <td colspan="2">10,000gp</td>
  <td class="tbl_value"><?php print(number_format($ti->t_cl*10000)); ?>gp</td>
 </tr>
 <tr>
  <th colspan="4" rowspan="2" class="tbl_title">Treasury:</th>
  <td rowspan="2"><?php print(number_format($ti->t_bank)); ?>gp</td>
  <th colspan="3" class="tbl_title">Apothecary:</th>
  <td><?php print($ti->t_apoc); ?></td>
  <th class="tbl_enchance">X</th>
  <td colspan="2">50,000gp</td>
  <td class="tbl_value"><?php print(number_format($ti->t_apoc*50000)); ?>gp</td>
 </tr>
 <tr>
  <td height="24" colspan="8">&nbsp;</td>
 </tr>
 <tr>
  <th colspan="4" class="tbl_title">Head Coach:</th>
  <td><?php print($ti->t_hcoach); ?> (<?php print($ti->display_name); ?>)</td>
  <th colspan="7" class="tbl_title">Total Value of Team (TV):</th>
  <td class="tbl_value"><?php print(number_format($ti->t_tv)); ?>gp</td>
 </tr>
</table>

		<?php endwhile;?>
	<?php endif; ?>

		</div> <!-- End of #maincontent -->
	</div> <!-- End of #pagecontent -->
	<div id="footer">
				<p>Unique content is &copy; <a href="<?php echo get_option('home'); ?>" title="Visit the homepage of the HDWSBBL">HDWSBBL</a> 2006 - present.</p>
				<p>Blood Bowl concept and miniatures are &copy; Games Workshop LTD used without permission.</p>
				<?php wp_footer(); ?>
	</div> <!-- End of #footer -->
</div> <!-- End of #wrapper -->
</body>
</html>