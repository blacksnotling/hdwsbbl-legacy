<?php
/*
Template Name: Statistics
*/
/*
*	Filename: bb.view.stats.php
*	Description: .Page template to display statistics
*/
?>
<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		<div id="breadcrumb">
			<p><a href="<?php echo get_option('home'); ?>" title="Back to the front of the HDWSBBL">HDWSBBL</a> &raquo; <?php the_title(); ?></p>
		</div>
			<div class="entry">
				<h2><?php the_title(); ?></h2>

				<?php the_content('Read the rest of this entry &raquo;'); ?>

<?php
				$matchnumsql = 'SELECT COUNT(*) AS MATCHNUM FROM '.$wpdb->prefix.'match M, '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE M.c_id = C.c_id AND C.c_counts = 1 AND C.c_show = 1 AND C.type_id = 1 AND M.m_id = J.tid AND J.prefix = \'m_\' AND J.pid = P.ID';
				$matchnum = $wpdb->get_var($matchnumsql);
				$compnumsql = 'SELECT COUNT(*) AS compnum FROM '.$wpdb->prefix.'comp M, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE M.c_counts = 1 AND M.c_show = 1 AND M.type_id = 1 AND M.c_id = J.tid AND J.prefix = \'c_\' AND J.pid = P.ID';
				$compnum = $wpdb->get_var($compnumsql);
				$cupnumsql = 'SELECT COUNT(*) AS cupnum FROM '.$wpdb->prefix.'series M, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE M.series_id = J.tid AND M.series_show = 1 AND J.prefix = \'series_\' AND J.pid = P.ID';
				$cupnum = $wpdb->get_var($cupnumsql);
				$playernumsql = 'SELECT COUNT(*) AS playernum FROM '.$wpdb->prefix.'player M, '.$wpdb->prefix.'team T, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE M.t_id = T.t_id AND T.t_show = 1 AND T.type_id = 1 AND M.p_id = J.tid AND J.prefix = \'p_\' AND J.pid = P.ID';
				$playernum = $wpdb->get_var($playernumsql);
				$teamnumsql = 'SELECT COUNT(*) AS teamnum FROM '.$wpdb->prefix.'team M, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE M.t_show = 1 AND M.type_id = 1 AND M.t_id = J.tid AND J.prefix = \'t_\' AND J.pid = P.ID';
				$teamnum = $wpdb->get_var($teamnumsql);
				$seanumsql = 'SELECT COUNT(*) AS seanum FROM '.$wpdb->prefix.'season M, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE M.sea_id = J.tid AND J.prefix = \'sea_\' AND J.pid = P.ID';
				$seanum = $wpdb->get_var($seanumsql);
				$sppnumsql = 'SELECT SUM(M.p_spp) AS sppnum FROM '.$wpdb->prefix.'player M, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P, '.$wpdb->prefix.'team T WHERE T.t_id = M.t_id AND T.type_id = 1 AND M.p_id = J.tid AND J.prefix = \'p_\' AND J.pid = P.ID AND M.p_spp > 0';
				$sppnum = $wpdb->get_var($sppnumsql);
				$deathnumsql = 'SELECT COUNT(F.f_id) AS DEAD FROM '.$wpdb->prefix.'player_fate F, '.$wpdb->prefix.'match M, '.$wpdb->prefix.'comp C WHERE (F.f_id = 1 OR F.f_id = 6) AND F.m_id = M.m_id AND M.c_id = C.c_id AND C.c_counts = 1 AND C.c_show = 1 AND C.type_id = 1';
				$deathnum = $wpdb->get_var($deathnumsql);

				$matchstatssql = 'SELECT SUM(M.m_tottd) AS TD, SUM(M.m_totcas) AS CAS, SUM(M.m_totcomp) AS COMP, SUM(M.m_totint) AS MINT FROM '.$wpdb->prefix.'match M, '.$wpdb->prefix.'comp C WHERE M.c_id = C.c_id AND C.c_counts = 1 AND C.c_show = 1 AND C.type_id = 1';
				if ($matchstats = $wpdb->get_results($matchstatssql)) {
					foreach ($matchstats as $ms) {
						$tottd = $ms->TD;
						$totcas = $ms->CAS;
						$totcomp = $ms->COMP;
						$totint = $ms->MINT;
					}
				}
?>

				<h3>Overall Statistics</h3>

				<p>Since the <strong>HDWSBBL's</strong> inception, <strong><?php print($playernum); ?></strong> Players in <strong><?php print($teamnum); ?></strong> Teams have played <strong><?php print($matchnum); ?></strong> Matches in <strong><?php print($compnum); ?></strong> Competitions for <strong><?php print($cupnum); ?></strong> Championship Cups over <strong><?php print($seanum); ?></strong> Seasons. In total they have managed to:</p>
				<ul>
					<li>Score <strong><?php print($tottd); ?></strong> Touchdowns (average <strong><?php print(round($tottd/$matchnum,1)); ?></strong> per match);</li>
					<li>Make <strong><?php print($totcomp); ?></strong> successful Completions (average <strong><?php print(round($totcomp/$matchnum,1)); ?></strong> per match);</li>
					<li>Cause <strong><?php print($totcas); ?></strong> Casualties (average <strong><?php print(round($totcas/$matchnum,1)); ?></strong> per match);</li>
					<li>Catch <strong><?php print($totint); ?></strong> Interceptions (average <strong><?php print(round($totint/$matchnum,1)); ?></strong> per match).</li>
					<li>Kill <strong><?php print($deathnum); ?></strong> players (average <strong><?php print(round($deathnum/$matchnum,1)); ?></strong> per match).</li>
					<li>Earn a total of <strong><?php print($sppnum); ?></strong> Star Player Points.</li>
				</ul>

				<h3>HDWSBBL Cup Winners</h3>
<?php
				$championssql = 'SELECT COUNT(A.a_name) AS ANUM, P.post_title, P.guid FROM '.$wpdb->prefix.'awards_team_comp T, '.$wpdb->prefix.'awards A, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P, '.$wpdb->prefix.'comp C WHERE T.c_id = C.c_id AND T.t_id = J.tid AND J.prefix = \'t_\' AND J.pid = P.ID AND A.a_id = 1 AND A.a_id = T.a_id AND C.type_id = 1 GROUP BY T.t_id ORDER BY ANUM DESC, P.post_title ASC';
				if ($champions = $wpdb->get_results($championssql)) {
					$zebracount = 1;
					print("	<table>\n		<tr>\n			<th class=\"tbl_name\">Team</th>\n			<th class=\"tbl_stat\">Championships</th>\n		</tr>\n");
					foreach ($champions as $champ) {
						if ($zebracount % 2) {
							print("		<tr>\n");
						}
						else {
							print("		<tr class=\"tbl_alt\">\n");
						}
						print("			<td><a href=\"".$champ->guid."\" title=\"View more about ".$champ->post_title."\">".$champ->post_title."</a></td>\n			<td>".$champ->ANUM."</td>\n		</tr>\n");
						$zebracount++;
					}
					print("	</table>\n");
				}
?>



				<h3>Statistics Breakdown by Season</h3>
<?php
				$seasonsql = 'SELECT O.post_title, O.guid, COUNT(m_id)AS NUMMAT, SUM(M.m_tottd) AS TD, SUM(M.m_totcas) AS CAS, SUM(M.m_totcomp) AS COMP, SUM(M.m_totint) AS MINT FROM '.$wpdb->prefix.'match M, '.$wpdb->prefix.'season S, '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' O WHERE S.sea_id = J.tid AND J.prefix = \'sea_\' AND J.pid = O.ID AND M.c_id = C.c_id AND C.sea_id = S.sea_id AND C.c_counts = 1 AND C.c_show = 1 AND C.type_id = 1 GROUP BY S.sea_name ORDER BY S.sea_id DESC';
				if ($seasonstats = $wpdb->get_results($seasonsql)) {
					print("<table class=\"sortable\">\n	<thead>\n	<tr>\n		<th class=\"tbl_name\">Season</th>\n		<th class=\"tbl_stat\">Games</th>\n		<th class=\"tbl_stat\">TD</th>\n		<th class=\"tbl_stat\">CAS</th>\n		<th class=\"tbl_stat\">COMP</th>\n		<th class=\"tbl_stat\">INT</th>\n	</tr>\n	</thead>\n	<tbody>\n");
					$zebracount = 1;
					foreach ($seasonstats as $ss) {
						if ($zebracount % 2) {
							print("		<tr>\n");
						}
						else {
							print("		<tr class=\"tbl_alt\">\n");
						}
						print("		<td><a href=\"".$ss->guid."\" title=\"Read more about ".$ss->post_title."\">".$ss->post_title."</a></td>\n		<td>".$ss->NUMMAT."</td>\n		<td>".$ss->TD."</td>\n		<td>".$ss->CAS."</td>\n		<td>".$ss->COMP."</td>\n		<td>".$ss->MINT."</td>\n	</tr>\n");
						$zebracount++;
					}
					print("</tbody>\n</table>\n");

				}
?>
				<h3>Statistics Breakdown by Competition</h3>
<?php
				$compsql = 'SELECT P.post_title, P.guid, COUNT(m_id)AS NUMMAT, SUM(M.m_tottd) AS TD, SUM(M.m_totcas) AS CAS, SUM(M.m_totcomp) AS COMP, SUM(M.m_totint) AS MINT FROM '.$wpdb->prefix.'match M, '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE C.c_id = J.tid AND J.prefix = \'c_\' AND J.pid = P.ID AND M.c_id = C.c_id AND C.c_counts = 1 AND C.c_show = 1 AND C.type_id = 1 GROUP BY C.c_id ORDER BY C.c_sdate DESC';
				if ($compstats = $wpdb->get_results($compsql)) {
					print("<table class=\"sortable\">\n	<thead>\n	<tr>\n		<th class=\"tbl_name\">Competition</th>\n		<th class=\"tbl_stat\">Games</th>\n		<th class=\"tbl_stat\">TD</th>\n		<th class=\"tbl_stat\">CAS</th>\n		<th class=\"tbl_stat\">COMP</th>\n		<th class=\"tbl_stat\">INT</th>\n	</tr>\n	</thead>\n	<tbody>\n");
					$zebracount = 1;
					foreach ($compstats as $ss) {
						if ($zebracount % 2) {
							print("	<tr>\n");
						}
						else {
							print("	<tr class=\"tbl_alt\">\n");
						}
						print("		<td><a href=\"".$ss->guid."\" title=\"Read more on ".$ss->post_title."\">".$ss->post_title."</a></td>\n		<td>".$ss->NUMMAT."</td>\n		<td>".$ss->TD."</td>\n		<td>".$ss->CAS."</td>\n		<td>".$ss->COMP."</td>\n		<td>".$ss->MINT."</td>\n	</tr>\n");
						$zebracount++;
					}
					print("	</tbody>\n</table>\n");

				}
?>

				<h3>Statistics Breakdown by Teams</h3>
				<table class="sortable">
					<thead>
					<tr>
						<th class="tbl_name">Team</th>
						<th class="tbl_stat">P</th>
						<th class="tbl_stat">W</th>
						<th class="tbl_stat">L</th>
						<th class="tbl_stat">D</th>
						<th class="tbl_stat">TF</th>
						<th class="tbl_stat">TA</th>
						<th class="tbl_stat">CF</th>
						<th class="tbl_stat">CA</th>
						<th class="tbl_stat">COMP</th>
						<th class="tbl_stat">INT</th>
						<th class="tbl_stat">Win%</th>
					</tr>
					</thead>
					<tbody>

<?php
				//$teamstatssql = 'SELECT P.post_title, SUM(T.tc_played) AS TP, SUM(T.tc_W) AS TW, SUM(T.tc_L) AS TL, SUM(T.tc_D) AS TD, SUM(T.tc_tdfor) AS TDF, SUM(T.tc_tdagst) AS TDA, SUM(T.tc_casfor) AS TCF, SUM(T.tc_casagst) AS TCA, SUM(T.tc_INT) AS TI, SUM(T.tc_comp) AS TC, P.guid FROM '.$wpdb->prefix.'team_comp T, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P, '.$wpdb->prefix.'comp C WHERE C.c_id = T.c_id AND C.c_counts = 1 AND C.c_show = 1 AND C.type_id = 1 AND T.t_id = J.tid AND J.prefix = \'t_\' AND J.pid = P.ID GROUP BY T.t_id ORDER BY P.post_title ASC LIMIT 0, 30 ';
				$teamstatssql = 'SELECT P.post_title, SUM(T.tc_played) AS TP, SUM(T.tc_W) AS TW, SUM(T.tc_L) AS TL, SUM(T.tc_D) AS TD, SUM(T.tc_tdfor) AS TDF, SUM(T.tc_tdagst) AS TDA, SUM(T.tc_casfor) AS TCF, SUM(T.tc_casagst) AS TCA, SUM(T.tc_INT) AS TI, SUM(T.tc_comp) AS TC, P.guid FROM '.$wpdb->prefix.'team_comp T, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P, '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'team Z WHERE Z.t_id = T.t_id AND Z.t_show = 1 AND C.c_id = T.c_id AND C.c_counts = 1 AND C.c_show = 1 AND C.type_id = 1 AND T.t_id = J.tid AND J.prefix = \'t_\' AND J.pid = P.ID GROUP BY T.t_id ORDER BY P.post_title ASC';
				if ($teamstats = $wpdb->get_results($teamstatssql)) {
					$zebracount = 1;

					foreach ($teamstats as $tst) {
						if ($zebracount % 2) {
							print("					<tr>\n");
						}
						else {
							print("					<tr class=\"tbl_alt\">\n");
						}
						print("						<td><a href=\"".$tst->guid."\" title=\"Read more on ".$tst->post_title."\">".$tst->post_title."</a></td>\n						<td>".$tst->TP."</td>\n						<td>".$tst->TW."</td>\n						<td>".$tst->TL."</td>\n						<td>".$tst->TD."</td>\n						<td>".$tst->TDF."</td>\n						<td>".$tst->TDA."</td>\n						<td>".$tst->TCF."</td>\n						<td>".$tst->TCA."</td>\n						<td>".$tst->TC."</td>\n						<td>".$tst->TI."</td>\n						");
						if ($tst->TP > 0) {
							print("<td>".number_format((($tst->TW/$tst->TP)*100))."%</td>\n");
						}
						else {
							print("<td>N/A</td>\n");
						}
						print("					</tr>\n");
						$zebracount++;
					}

				}
?>
				</tbody>
				</table>


				<h3>Detailed Statistics Breakdown</h3>
				<p>This page only covers the high level statistics.The following links will take you through to more detailed pages.</p>
				<div id="statslinkscontainer">
					<ol id="statslinks">
						<li><a href="<?php echo get_option('home'); ?>/stats/td/" title="View more Touchdown related Statistics">Touchdown Statistics</a></li>
						<li><a href="<?php echo get_option('home'); ?>/stats/cas/" title="View more Casualty related Statistics">Casualty Statistics </a></li>
						<li><a href="<?php echo get_option('home'); ?>/stats/misc/" title="View more Miscellaneous Statistics">Miscellaneous Statistics </a></li>
<!--						<li><a href="<?php echo get_option('home'); ?>/stats/records/" title="View Match Records">Match Records</a></li> -->
						<li><a href="<?php echo get_option('home'); ?>/stats/awards/" title="View The Awards that have been assigned in the league">Awards</a></li>
						<li><a href="<?php echo get_option('home'); ?>/stats/milestones/" title="View the HDWSBBL Milestones">Milestones</a></li>
					</ol>
				</div>



				<h3>Statistics Breakdown by Players</h3>
<?php
				$options = get_option('bblm_config');
				$stat_limit = htmlspecialchars($options['display_stats'], ENT_QUOTES);
				$bblm_star_team = htmlspecialchars($options['team_star'], ENT_QUOTES);

				  ////////////////////////
				 // Active Top Players //
				////////////////////////
				$statsql = 'SELECT Y.post_title, T.t_name AS TEAM, T.t_guid AS TEAMLink, Y.guid, SUM(M.mp_spp) AS VALUE, R.pos_name FROM '.$wpdb->prefix.'player P, '.$wpdb->prefix.'team T, '.$wpdb->prefix.'match_player M, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' Y, '.$wpdb->prefix.'position R WHERE P.pos_id = R.pos_id AND P.p_id = J.tid AND J.prefix = \'p_\' AND J.pid = Y.ID AND M.p_id = P.p_id AND P.t_id = T.t_id AND M.mp_counts = 1 AND M.mp_spp > 0 AND T.t_active = 1 AND P.p_status = 1 AND T.t_id != '.$bblm_star_team.' GROUP BY P.p_id ORDER BY VALUE DESC LIMIT '.$stat_limit;
				print("<h4>Top Players (Active)</h4>\n");
				if ($topstats = $wpdb->get_results($statsql)) {
					print("<table class=\"expandable\">\n	<tr>\n		<th class=\"tbl_stat\">#</th>\n		<th class=\"tbl_name\">Player</th>\n		<th>Position</th>\n		<th class=\"tbl_name\">Team</th>\n		<th class=\"tbl_stat\">SPP</th>\n		</tr>\n");
					$zebracount = 1;
					$prevvalue = 0;

					foreach ($topstats as $ts) {
						if (($zebracount % 2) && (10 < $zebracount)) {
							print("	<tr class=\"tb_hide\">\n");
						}
						else if (($zebracount % 2) && (10 >= $zebracount)) {
							print("	<tr>\n");
						}
						else if (10 < $zebracount) {
							print("	<tr class=\"tbl_alt tb_hide\">\n");
						}
						else {
							print("	<tr class=\"tbl_alt\">\n");
						}
						if ($ts->VALUE > 0) {
							if ($prevvalue == $ts->VALUE) {
								print("	<td>-</td>\n");
							}
							else {
								print("	<td><strong>".$zebracount."</strong></td>\n");
							}
							print("	<td><a href=\"".$ts->guid."\" title=\"View more details on ".$ts->post_title."\">".$ts->post_title."</a></td>\n	<td>".$ts->pos_name."</td>\n	<td><a href=\"".$ts->TEAMLink."\" title=\"Read more on this team\">".$ts->TEAM."</a></td>\n	<td>".$ts->VALUE."</td>\n	</tr>\n");
							$prevvalue = $ts->VALUE;
						}
						$zebracount++;
					}
					print("</table>\n");
				}
				else {
					print("	<div class=\"info\">\n		<p>No active players have scored any Star Player Points!</p>\n	</div>\n");
				}

				  //////////////////////////
				 // All time Top Players //
				//////////////////////////
				$statsql = 'SELECT Y.post_title, T.t_name AS TEAM, T.t_guid AS TEAMLink, Y.guid, SUM(M.mp_spp) AS VALUE, R.pos_name, T.t_active, P.p_status FROM '.$wpdb->prefix.'player P, '.$wpdb->prefix.'team T, '.$wpdb->prefix.'match_player M, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' Y, '.$wpdb->prefix.'position R WHERE P.pos_id = R.pos_id AND P.p_id = J.tid AND J.prefix = \'p_\' AND J.pid = Y.ID AND M.p_id = P.p_id AND P.t_id = T.t_id AND M.mp_spp > 0 AND M.mp_counts = 1 AND T.t_id != '.$bblm_star_team.' GROUP BY P.p_id ORDER BY VALUE DESC LIMIT '.$stat_limit;
				print("<h4>Top Players (All Time)</h4>\n	<p>Players who are <strong>highlighted</strong> are still active in the HDWSBBL.</p>\n");
				if ($topstats = $wpdb->get_results($statsql)) {
					print("<table class=\"expandable\">\n	<tr>\n		<th class=\"tbl_stat\">#</th>\n		<th class=\"tbl_name\">Player</th>\n		<th>Position</th>\n		<th class=\"tbl_name\">Team</th>\n		<th class=\"tbl_stat\">SPP</th>\n		</tr>\n");
					$zebracount = 1;
					$prevvalue = 0;

					foreach ($topstats as $ts) {
						if (($zebracount % 2) && (10 < $zebracount)) {
							print("	<tr class=\"tb_hide\">\n");
						}
						else if (($zebracount % 2) && (10 >= $zebracount)) {
							print("	<tr>\n");
						}
						else if (10 < $zebracount) {
							print("	<tr class=\"tbl_alt tb_hide\">\n");
						}
						else {
							print("	<tr class=\"tbl_alt\">\n");
						}
						if ($ts->VALUE > 0) {
							if ($prevvalue == $ts->VALUE) {
								print("	<td>-</td>\n");
							}
							else {
								print("	<td><strong>".$zebracount."</strong></td>\n");
							}
							print("	<td><a href=\"".$ts->guid."\" title=\"View more details on ".$ts->post_title."\">");
							if ($ts->t_active && $ts->p_status) {
								print("<strong>".$ts->post_title."</strong>");
							}
							else {
								print($ts->post_title);
							}
							print("</a></td>\n	<td>".$ts->pos_name."</td>\n	<td><a href=\"".$ts->TEAMLink."\" title=\"Read more on this team\">".$ts->TEAM."</a></td>\n	<td>".$ts->VALUE."</td>\n	</tr>\n");
							$prevvalue = $ts->VALUE;
						}
						$zebracount++;
					}
					print("</table>\n");
				}
				else {
					print("	<div class=\"info\">\n		<p>Nobody has scored any Star Player Points!</p>\n	</div>\n");
				}

		//Did You Know Display Code
		if (function_exists(bblm_display_dyk)) {
			bblm_display_dyk();
		}
?>
				<p class="postmeta"><?php edit_post_link('Edit', ' <strong>[</strong> ', ' <strong>]</strong> '); ?></p>

			</div>


		<?php endwhile;?>
	<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>