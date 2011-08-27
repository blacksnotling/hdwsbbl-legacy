<?php
/*
Template Name: View Team
*/
/*
*	Filename: bb.view.team.php
*	Version: 1.2
*	Description: .Page template to display
*/
/* -- Change History --
20080305 - 0.1a - Intital creation of file
20080317 - 0.1b - Mass adding of stuff with Alex here!
20080417 - 0.2b - Added in players and TV quickly for help debugging
20080418 - 0.3b - db was being called incorectly. have fixed it.
20080604 - 2.0b - Started again from scratch
20080608 - 2.1b - Complemented season beakdowm, comp breakdown and recent form
20080609 - 2.2b - Implemented recent matches
20080610 - 2.3b - added zebra striping and other classes to the tables, awards, sidebars and other bits and bobs.
20080611 - 2.4b - added Breadbrumbs
20080617 - 2.5b - Added "Currently participating in:" to the sidebar
20080619 - 2.5.1b - swapped the TDf anf TDa on the seaon report as it was the wrong way round!
20080622 - 2.5.2b - Added a missing closing tag to a table.
20080623 - 2.5.3b - Review of table classes
20080624 - 2.6b -fixed the "participating in" sidebar bit. ammended the awards list layout (in page and sidebar)
20080625 - 2.7b - moved a whole ot of info the the side bards, added win % and fixed a small bug (see 2.5.1 but for comps!)
20080702 - 2.7.1b - added some thead and tbody tags to the sortable tables
20080707 - 2.7.2b - fixed a potential error when calcuating win %
20080717 - 2.7.3b - Swapped Att for winnings in the recent matches due to the db being messed up!
20080722 - 2.7.4b - deleted a rogue </ul> from the sidebar that was messing it up when a team was inactive / etween season
20080730 - 1.0 - bump to Version 1 for public release.
20080831 - 1.0.1 - emergancy fix to restore the roster link for teams who havn't had their debut.
20090710 - 1.0.2 - Added DYK code to page
20090715 - 1.0.2r1 - Began revision of page. I added team logos, the Captain is highlighted, and more information is added to the match history.
20090716 - 1.0.2r2 - Finished match comments code, added team fixtures and reviewed the awards code
		 - 1.1 - rounded off changes and calling them complete for now.
20091129 - 1.1.1 - The table for season perfromace incorrectly said competition. I have corrected this to Season! (tracker [219]
20100123 - 1.2 - Updated the prefix for the custom bb tables in the Database (tracker [225])
*/
?>
<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		<div id="breadcrumb">
			<p><a href="<?php echo get_option('home'); ?>" title="Back to the front of the HDWSBBL">HDWSBBL</a> &raquo; <a href="<?php echo get_option('home'); ?>/teams/" title="Back to the team listing">Teams</a> &raquo; <?php the_title(); ?></p>
		</div>
			<div class="entry">
				<h2><?php the_title(); ?></h2>
<?
		$teaminfosql = 'SELECT T.*, J.tid AS teamid, R.r_name, L.guid AS racelink, U.display_name, W.post_title AS stad, W.guid AS stadlink FROM '.$wpdb->prefix.'team T, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P, '.$wpdb->users.' U, '.$wpdb->prefix.'race R, '.$wpdb->prefix.'bb2wp K, '.$wpdb->posts.' L, '.$wpdb->prefix.'bb2wp Q, '.$wpdb->posts.' W WHERE T.stad_id = Q.tid AND Q.prefix = \'stad_\' AND Q.pid = W.ID AND T.r_id = K.tid AND K.prefix = \'r_\' AND K.pid = L.ID AND T.ID = U.ID AND R.r_id = T.r_id AND T.t_id = J.tid AND J.prefix = \'t_\' AND J.pid = P.ID AND P.ID = '.$post->ID;
		if ($ti = $wpdb->get_row($teaminfosql)) {
				$tid = $ti->teamid;

				if ($ti->t_roster) {
					$rosterlinksql = 'SELECT P.guid FROM '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE J.prefix = \'roster\' AND J.pid = P.ID AND J.tid = '.$tid;
					$rosterlink = $wpdb->get_var($rosterlinksql);
				}

				//Determine if a custom logo is present
				$filename = $_SERVER['DOCUMENT_ROOT']."/images/teams/".$ti->t_sname."_big.gif";
				if (file_exists($filename)) {
					$timg = "<img src=\"".get_option('home')."/images/teams/".$ti->t_sname."_big.gif\" alt=\"".$ti->t_sname." Logo\" />";
				}
				else {
					$timg = "<img src=\"".get_option('home')."/images/races/race".$ti->r_id.".gif\" alt=\"".$ti->r_name." Logo\" />";
				}
		}
?>
				<div class="details team">
					<?php the_content('Read the rest of this entry &raquo;'); ?>
				</div>
<?php
			//Set default value to flag if the team has played a game or not
			$has_played = 1;

			$overallsql = "SELECT SUM(T.tc_played) AS OP, SUM(T.tc_W) AS OW, SUM(T.tc_L) AS OL, SUM(T.tc_D) AS OD, SUM(T.tc_tdfor) AS OTF, SUM(T.tc_tdagst) AS OTA, SUM(T.tc_comp) AS OC, SUM(T.tc_casfor) AS OCASF, SUM(T.tc_casagst) AS OCASA, SUM(T.tc_int) AS OINT FROM ".$wpdb->prefix."team_comp T, ".$wpdb->prefix."comp C WHERE C.c_counts = 1 AND C.c_id = T.c_id AND T.tc_played > 0 AND T.t_id = ".$tid;

			if ($oh = $wpdb->get_row($overallsql)) {
				if (NULL != $oh->OP) {
?>
				<h3>Career Statistics for <?php the_title(); ?></h3>
				<table>
					<tr>
						<th class="tbl_title">Team</th>
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
						<th class="tbl_stat">%</th>
					</tr>
					<tr>
						<td><?php the_title(); ?></td>
						<td><?php print($oh->OP); ?></td>
						<td><?php print($oh->OW); ?></td>
						<td><?php print($oh->OL); ?></td>
						<td><?php print($oh->OD); ?></td>
						<td><?php print($oh->OTF); ?></td>
						<td><?php print($oh->OTA); ?></td>
						<td><?php print($oh->OCASF); ?></td>
						<td><?php print($oh->OCASA); ?></td>
						<td><?php print($oh->OC); ?></td>
						<td><?php print($oh->OINT); ?></td>
						<td><?php if ($oh->OP > 0) {print(number_format(($oh->OW/$oh->OP)*100)); } else {print("N/A"); } ?></td>
					</tr>
				</table>

				<h4>Key</h4>
				<ul class="expandablekey">
					<li><strong>P</strong>: Number of games Played</li>
					<li><strong>TF</strong>: Number of Touchdowns scored by the team</li>
					<li><strong>TA</strong>: Number of Touchdowns scored against the team</li>
					<li><strong>CF</strong>: Number of casulties caused by the team</li>
					<li><strong>CA</strong>: Number of casulties the team has suffered</li>
					<li><strong>%</strong>: Teams win percentage (including Draws)</li>
				</ul>

				<h3>Performance by Season</h3>
<?php
			$seasonsql = 'SELECT P.post_title, P.guid, SUM(T.tc_played) AS PLD, SUM(T.tc_W) AS win, SUM(T.tc_L) AS lose, SUM(T.tc_D) AS draw, SUM(T.tc_tdfor) AS TDf, SUM(T.tc_tdagst) AS TDa, SUM(T.tc_casfor) AS CASf, SUM(T.tc_casagst) AS CASa, SUM(T.tc_comp) AS COMP, SUM(T.tc_int) AS cINT FROM '.$wpdb->prefix.'team_comp T, '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P, '.$wpdb->prefix.'season S WHERE T.c_id = C.c_id AND C.sea_id = S.sea_id AND J.tid = C.sea_id AND J.prefix = \'sea_\' AND J.pid = P.ID AND tc_played > 0 AND C.c_counts = 1 AND C.c_show = 1 AND T.t_id = '.$tid.' GROUP BY C.sea_id ORDER BY S.sea_id DESC';
			if ($seah = $wpdb->get_results($seasonsql)) {
				$zebracount = 1;
				print("	<table class=\"sortable\">\n	<thead>\n		<tr>\n			<th class=\"tbl_title\">Season</th>\n			<th class=\"tbl_stat\">P</th>\n			<th class=\"tbl_stat\">W</th>\n			<th class=\"tbl_stat\">L</th>\n			<th class=\"tbl_stat\">D</th>\n			<th class=\"tbl_stat\">TF</th>\n			<th class=\"tbl_stat\">TA</th>\n			<th class=\"tbl_stat\">CF</th>\n			<th class=\"tbl_stat\">CA</th>\n			<th class=\"tbl_stat\">COMP</th>\n			<th class=\"tbl_stat\">INT</th>\n			<th class=\"tbl_stat\">%</th>\n		</tr>\n	</thead>\n	<tbody>\n");

				foreach ($seah as $sh) {
					if ($zebracount % 2) {
						print("		<tr>\n");
					}
					else {
						print("		<tr class=\"tbl_alt\">\n");
					}
					print("			<td><a href=\"".$sh->guid."\" title=\"View more info about ".$sh->post_title."\">".$sh->post_title."</a></td>\n			<td>".$sh->PLD."</td>\n			<td>".$sh->win."</td>\n			<td>".$sh->lose."</td>\n			<td>".$sh->draw."</td>\n			<td>".$sh->TDf."</td>\n			<td>".$sh->TDa."</td>\n			<td>".$sh->CASf."</td>\n			<td>".$sh->CASa."</td>\n			<td>".$sh->COMP."</td>\n			<td>".$sh->cINT."</td>\n			");
					if ($sh->PLD >0) {
						print("<td>".number_format(($sh->win/$sh->PLD)*100)."</td>\n");
					}
					else {
						print("<td>N/A</td>\n");
					}
					print("		</tr>\n");


					$zebracount++;
				}
				print("	</tbody>\n	</table>\n");
			}

?>
				<h3>Performance by Competition</h3>

<?php
			//$matchhsql = "SELECT P.guid, T.*, C.c_name FROM ".$wpdb->prefix."team_comp T, ".$wpdb->prefix."comp C, ".$wpdb->prefix."bb2wp J, ".$wpdb->posts." P WHERE T.c_id = C.c_id AND J.tid = C.c_id AND J.prefix = 'c_' AND J.pid = P.ID AND tc_played > 0 AND C.c_counts = 1 AND T.t_id =".$tid." GROUP BY C.c_id";
			$matchhsql = 'SELECT P.post_title, P.guid, SUM(T.tc_played) AS PLD, SUM(T.tc_W) AS win, SUM(T.tc_L) AS lose, SUM(T.tc_D) AS draw, SUM(T.tc_tdfor) AS TDf, SUM(T.tc_tdagst) AS TDa, SUM(T.tc_casfor) AS CASf, SUM(T.tc_casagst) AS CASa, SUM(T.tc_comp) AS COMP, SUM(T.tc_int) AS cINT FROM '.$wpdb->prefix.'team_comp T, '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE T.c_id = C.c_id AND J.tid = C.c_id AND J.prefix = \'c_\' AND J.pid = P.ID AND tc_played > 0 AND C.c_counts = 1 AND C.c_show = 1 AND T.t_id = '.$tid.' GROUP BY C.c_id ORDER BY C.c_id DESC';

			if ($matchh = $wpdb->get_results($matchhsql)) {
				$zebracount = 1;
				print("	<table class=\"sortable\">\n	<thead>\n		<tr>\n			<th class=\"tbl_title\">Competition</th>\n			<th class=\"tbl_stat\">P</th>\n			<th class=\"tbl_stat\">W</th>\n			<th class=\"tbl_stat\">L</th>\n			<th class=\"tbl_stat\">D</th>\n			<th class=\"tbl_stat\">TF</th>\n			<th class=\"tbl_stat\">TA</th>\n			<th class=\"tbl_stat\">CF</th>\n			<th class=\"tbl_stat\">CA</th>\n			<th class=\"tbl_stat\">COMP</th>\n			<th class=\"tbl_stat\">INT</th>\n			<th class=\"tbl_stat\">%</th>\n		</tr>\n	</thead>\n	<tbody>\n");

				foreach ($matchh as $mh) {
					if ($zebracount % 2) {
						print("		<tr>\n");
					}
					else {
						print("		<tr class=\"tbl_alt\">\n");
					}
					print("			<td><a href=\"".$mh->guid."\" title=\"View more info about ".$mh->post_title."\">".$mh->post_title."</a></td>\n			<td>".$mh->PLD."</td>\n			<td>".$mh->win."</td>\n			<td>".$mh->lose."</td>\n			<td>".$mh->draw."</td>\n			<td>".$mh->TDf."</td>\n			<td>".$mh->TDa."</td>\n			<td>".$mh->CASf."</td>\n			<td>".$mh->CASa."</td>\n			<td>".$mh->COMP."</td>\n			<td>".$mh->cINT."</td>\n");
					if ($mh->PLD > 0) {
						print("			<td>".number_format(($mh->win/$mh->PLD)*100)."</td>\n");
					}
					else {
						print("			<td>N/A</td>\n");
					}
					print("		</tr>\n");

					$zebracount++;
				}
				print("</tbody>\n	</table>\n");
			}
?>



			<h3>Players</h3>
<?php
			//determine Team Captain
			$teamcaptainsql = 'SELECT * FROM '.$wpdb->prefix.'team_captain WHERE tcap_status = 1 and t_id = '.$tid;
			if ($tcap = $wpdb->get_row($teamcaptainsql)) {
				$teamcap = $tcap->p_id;
			}

			$playerssql = 'SELECT P.p_num, K.post_title, K.guid, L.pos_name, P.p_status, P.p_cost, P.p_cost_ng, P.p_id FROM '.$wpdb->prefix.'player P, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' K, '.$wpdb->prefix.'position L WHERE P.p_id = J.tid AND J.prefix = \'p_\' AND J.pid = K.ID AND P.pos_id = L.pos_id AND P.t_id = '.$tid.' ORDER BY P.p_status DESC, P.p_num ASC';
			if ($player = $wpdb->get_results($playerssql)) {
				$is_first = 1;
				$current_status = "";

				foreach ($player as $pd) {
					if ($current_status !== $pd->p_status) {
						if (!TRUE == $is_first) {
							print("			</ul>\n");
						}
						if ($pd->p_status) {
							$status_text = "Active Players";
						}
						else {
							$status_text = "Former Players";
						}
						print("			<h4>".$status_text."</h4>\n			<ul>\n");
					}
					$current_status = $pd->p_status;
						print("				<li>#".$pd->p_num." - <a href=\"".$pd->guid."\" title=\"View more information about ".$pd->post_title."\">".$pd->post_title."</a>");
						if ($teamcap == $pd->p_id) {
							print(" <strong>(Captain)</strong>");
							//Assignes the Captain to a link for future use in the Sidebar!
							$teamcaplink = "<a href=\"".$pd->guid."\" title=\"View more information about ".$pd->post_title."\">".$pd->post_title."</a>";
						}
						print(" - ".$pd->pos_name." (".number_format($pd->p_cost)."gp)</li>\n");

					$is_first = 0;
				}
				print("			</ul>\n");
				if ($ti->t_roster) {
					print("<p><a href=\"".$rosterlink."/\" title=\"View the teams full roster \">View Full Roster &gt;&gt;</a></p>");
				}
			}
			else {
				print("<div class=\"info\">\n	<p>No players have been found for this team.</p>\n	</div>\n");
			}

				$fixturesql = 'SELECT F.f_teamA, F.f_teamB, UNIX_TIMESTAMP(F.f_date) AS fdate, D.div_name, T.t_name AS tA, T.t_guid AS tAlink, Y.t_name AS tB, Y.t_guid AS tBlink, P.post_title AS Comp, P.guid AS CompLink FROM '.$wpdb->prefix.'fixture F, '.$wpdb->prefix.'division D, '.$wpdb->prefix.'team T, '.$wpdb->prefix.'team Y, '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE C.c_id = J.tid AND J.prefix = \'c_\' AND J.pid = P.ID AND (F.f_teamA = '.$tid.' OR F.f_teamB = '.$tid.') AND F.div_id = D.div_id AND F.f_teamA = T.t_id AND F.f_teamB = Y.t_id AND C.c_id = F.c_id AND C.c_counts = 1 AND F.f_complete = 0 ORDER BY f_date ASC LIMIT 0, 30 ';

			if ($fixtures = $wpdb->get_results($fixturesql)) {
				print("<h3>Upcoming Matches (Fixtures)</h3>\n\n");
				print("<table class=\"expandable\">\n		 <tr>\n		   <th class=\"tbl_matchdate\">Date</th>\n		   <th class=\"tbl_matchname\">opponent</th>\n		   <th class=\"tbl_matchname\">Competition</th>\n		 </tr>\n");

				$is_first = 0;
				$current_div = "";
				$zebracount = 1;

				//grab the ID of the "tbd" team
				$options = get_option('bblm_config');
				$bblm_tbd_team = htmlspecialchars($options['team_tbd'], ENT_QUOTES);


				foreach ($fixtures as $fd) {
					if (($zebracount % 2) && (10 < $zebracount)) {
						print("		 <tr class=\"tb_hide\">\n");
					}
					else if (($zebracount % 2) && (10 >= $zebracount)) {
						print("		 <tr>\n");
					}
					else if (10 < $zebracount) {
						print("		 <tr class=\"tbl_alt tb_hide\">\n");
					}
					else {
						print("		 <tr class=\"tbl_alt\">\n");
					}
					print("		 	<td>".date("d.m.y", $fd->fdate)."</td>\n		 	<td>\n");
					if ($tid == $fd->f_teamA) {
						if ($bblm_tbd_team == $fd->f_teamB) {
							print($fd->tB);
						}
						else {
							print("<a href=\"".$fd->tBlink."\" title=\"Learn more about ".$fd->tB."\">".$fd->tB."</a>");
						}
					}
					else if ($tid == $fd->f_teamB) {
						if ($bblm_tbd_team == $fd->f_teamA) {
							print($fd->tA);
						}
						else {
							print("<a href=\"".$fd->tAlink."\" title=\"Learn more about ".$fd->tA."\">".$fd->tA."</a>");
						}
					}
					print("</td>\n		 	<td><a href=\"".$fd->CompLink."\" title=\"Read more about ".$fd->Comp."\">".$fd->Comp."</a> (".$fd->div_name.")</td>\n			</tr>\n");
					$zebracount++;
				}
				print("</table>\n");
			} //end of if fixtures SQL










































?>
				<h3>Recent Matches</h3>
<?php
				$matchssql = 'SELECT M.m_id, S.post_title AS Mtitle, S.guid AS Mlink, P.post_title AS TAname, O.post_title AS TBname, P.guid AS TAlink, O.guid AS TBlink, UNIX_TIMESTAMP(M.m_date) AS mdate, N.mt_winnings, N.mt_att, N.mt_tv, N.mt_comment, N.mt_result, M.m_teamA, M.m_teamB, M.m_teamAtd, M.m_teamBtd, M.m_teamAcas, M.m_teamBcas FROM '.$wpdb->prefix.'match_team N, '.$wpdb->prefix.'match M, '.$wpdb->prefix.'team T, '.$wpdb->prefix.'team R, '.$wpdb->prefix.'bb2wp J, '.$wpdb->prefix.'bb2wp K, '.$wpdb->prefix.'bb2wp A, '.$wpdb->posts.' S, '.$wpdb->posts.' P, '.$wpdb->posts.' O, '.$wpdb->prefix.'comp C WHERE M.c_id = C.c_id AND C.c_show = 1 AND C.c_counts = 1 AND N.m_id = M.m_id AND T.t_id = J.tid AND R.t_id = K.tid AND J.prefix = \'t_\' AND K.prefix = \'t_\' AND J.pid = P.ID AND K.pid = O.ID AND M.m_teamA = T.t_id AND M.m_teamB = R.t_id AND M.m_id = A.tid AND A.prefix = \'m_\' AND A.pid = S.ID AND N.t_id = '.$tid.' ORDER BY M.m_date DESC';
				if ($matchs = $wpdb->get_results($matchssql)) {
				$zebracount = 1;
					print("<table class=\"sortable expandable\" id=\"recentmatches\">\n	<thead>\n		 <tr>\n		   <th>Date</th>\n		   <th class=\"tbl_matchname\">Opponent</th>\n		   <th class=\"tbl_stat\">TF</th>\n		   <th class=\"tbl_stat\">TA</th>\n		   <th class=\"tbl_stat\">CF</th>\n		   <th class=\"tbl_stat\">CA</th>\n		   <th>Fans</th>\n		   <th>TV</th>\n		   <th>Result</th>\n		 </tr>\n	</thead>\n	<tbody>\n");
					foreach ($matchs as $ms) {
						/*
						  This one is a little different, we check for zebra (as normal but if it is also over 10 then we need to add the hooks to collapse it.
						*/
						if (($zebracount % 2) && (10 < $zebracount)) {
							print("		<tr class=\"tb_hide\">\n");
						}
						else if (($zebracount % 2) && (10 >= $zebracount)) {
							print("		<tr>\n");
						}
						else if (10 < $zebracount) {
							print("		<tr class=\"tbl_alt tb_hide\">\n");
							$alt = TRUE;
						}
						else {
							print("		<tr class=\"tbl_alt\">\n");
							$alt = TRUE;
						}
						print("		   <td><a href=\"".$ms->Mlink."\" title=\"View full details of ".$ms->Mtitle."\">".date("d.m.y", $ms->mdate)."</a></td>\n		   <td class=\"tbl_matchop\">");

						if ($tid == $ms->m_teamA) {
							print("<a href=\"".$ms->TBlink."\" title=\"View more details about ".$ms->TBname."\">".$ms->TBname."</a></td>\n		   <td>".$ms->m_teamAtd."</td>\n		   <td>".$ms->m_teamBtd."</td>\n		   <td>".$ms->m_teamAcas."</td>\n		   <td>".$ms->m_teamBcas."</td>\n");
						}
						else if ($tid == $ms->m_teamB) {
							print("<a href=\"".$ms->TAlink."\" title=\"View more details about ".$ms->TAname."\">".$ms->TAname."</a></td>\n		   <td>".$ms->m_teamBtd."</td>\n		   <td>".$ms->m_teamAtd."</td>\n		   <td>".$ms->m_teamBcas."</td>\n		   <td>".$ms->m_teamAcas."</td>\n");
						}
						print("		   <td>".number_format($ms->mt_winnings)."</td>\n		   <td>".number_format($ms->mt_tv)."</td>\n		   <td>".$ms->mt_result."</td>\n		 </tr>\n");
						//printing of match comment
						print("		<tr id=\"mcomment-".$ms->m_id."\" class=\"mcomment");
						if ($alt) {
							print(" tbl_alt\">\n");
						}
						else {
							print("\">\n");
						}
						print("		   <td colspan=\"9\">".$ms->mt_comment."</td>\n		</tr>\n");

						$alt = FALSE;
						$zebracount++;
					}
					print("	</tbody>\n	</table>\n");
				}
?>


				<h3 id="awardsfull">Awards list in full</h3>
<?php
				$championshipssql = 'SELECT A.a_name, P.post_title, P.guid FROM '.$wpdb->prefix.'awards A, '.$wpdb->prefix.'awards_team_comp B, '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE A.a_id = B.a_id AND a_cup = 1 AND B.c_id = C.c_id AND C.c_id = J.tid AND J.prefix = \'c_\' AND J.pid = P.ID AND B.t_id = '.$tid.' ORDER BY A.a_id ASC';
				if ($champs = $wpdb->get_results($championshipssql)) {
					$has_cups = 1;
					$zebracount = 1;
					print("<h4>Championships</h4>\n");
					print("<table>\n	<tr>\n		<th class=\"tbl_name\">Title</th>\n		<th class=\"tbl_name\">Competition</th>\n	</tr>\n");
					foreach ($champs as $cc) {
						if ($zebracount % 2) {
							print("		<tr>\n");
						}
						else {
							print("		<tr class=\"tbl_alt\">\n");
						}
						print("		<td>".$cc->a_name."</td>\n		<td><a href=\"".$cc->guid."\" title=\"View full details about ".$cc->post_title."\">".$cc->post_title."</a></td>\n	</tr>\n");
						$zebracount++;
					}
					print("</table>\n");
				}
				else {
					$ccfail = 1;
				}

				$seasonsql = 'SELECT A.a_name, P.post_title, P.guid, B.ats_value FROM '.$wpdb->prefix.'awards A, '.$wpdb->prefix.'awards_team_sea B, '.$wpdb->prefix.'season C, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE A.a_id = B.a_id AND B.sea_id = C.sea_id AND C.sea_id = J.tid AND J.prefix = \'sea_\' AND J.pid = P.ID AND B.t_id = '.$tid.' ORDER BY A.a_id ASC';
				if ($sawards = $wpdb->get_results($seasonsql)) {
					$zebracount = 1;
					print("<h4>Awards from Seasons</h4>\n");
					print("<table>\n	<tr>\n		<th class=\"tbl_name\">Award</th>\n		<th class=\"tbl_name\">Competition</th>\n		<th class=\"tbl_stat\">Value</th>\n	</tr>\n");
					foreach ($sawards as $sa) {
						if ($zebracount % 2) {
							print("		<tr>\n");
						}
						else {
							print("		<tr class=\"tbl_alt\">\n");
						}
						print("		<td>".$sa->a_name."</td>\n		<td><a href=\"".$sa->guid."\" title=\"View full details about ".$sa->post_title."\">".$sa->post_title."</a></td>\n		<td>".$sa->ats_value."</td>\n	</tr>\n");
						$zebracount++;
					}
					print("</table>\n");
				}
				else {
					$safail = 1;
				}

				$compawardssql = 'SELECT A.a_name, P.post_title, P.guid, B.atc_value FROM '.$wpdb->prefix.'awards A, '.$wpdb->prefix.'awards_team_comp B, '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE A.a_id = B.a_id AND a_cup = 0 AND B.c_id = C.c_id AND C.c_id = J.tid AND J.prefix = \'c_\' AND J.pid = P.ID AND B.t_id = '.$tid.' ORDER BY A.a_id ASC';
				if ($cawards = $wpdb->get_results($compawardssql)) {
					$zebracount = 1;
					print("<h4>Awards from Competitions</h4>\n");
					print("<table>\n	<tr>\n		<th class=\"tbl_name\">Award</th>\n		<th class=\"tbl_name\">Competition</th>\n		<th class=\"tbl_stat\">Value</th>\n	</tr>\n");
					foreach ($cawards as $ca) {
						if ($zebracount % 2) {
							print("		<tr>\n");
						}
						else {
							print("		<tr class=\"tbl_alt\">\n");
						}
						print("		<td>".$ca->a_name."</td>\n		<td><a href=\"".$ca->guid."\" title=\"View full details about ".$ca->post_title."\">".$ca->post_title."</a></td>\n		<td>".$ca->atc_value."</td>\n	</tr>\n");
						$zebracount++;
					}
					print("</table>\n");
				}
				else {
					$cafail = 1;
				}

				if ($cafail && $safail && $ccfail) {
					//no awards at all
					print("	<div class=\"info\">\n		<p>This team has not any awards as of yet.</p>\n	</div>\n");
				}


				}//end of count stats
				else {
					$has_played = 0;
					print("	<div class=\"info\">\n		<p>This Team has not yet made their debut in the HDWSBBL. Stay tuned to see how this team develops.</p>\n	</div>\n");
				}
			}//end of db stats query

		//Did You Know Display Code
		if (function_exists(bblm_display_dyk)) {
			bblm_display_dyk();
		}
?>

				<p class="postmeta"><?php edit_post_link('Edit', ' <strong>[</strong> ', ' <strong>]</strong> '); ?></p>

			</div>


		<?php endwhile;?>
	<?php endif; ?>

</div><!-- end of #maincontent -->
<?php
		//Gathering data for the sidebar
		//Current match form
		$formsql = 'SELECT R.mt_result FROM '.$wpdb->prefix.'match_team R, '.$wpdb->prefix.'match M, '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE M.m_id = J.tid AND J.prefix = \'m_\' AND J.tid = P.ID AND M.c_id = C.c_id AND C.c_counts = 1 AND C.c_show = 1 AND M.m_id = R.m_id AND R.t_id = '.$tid.' ORDER BY m_date DESC LIMIT 5';
		$currentform = "";
		if ($form = $wpdb->get_results($formsql)) {
			foreach ($form as $tf) {
				$currentform .= $tf->mt_result;
			}
		}
		else {
				$currentform = "N/A";
		}


	//determine debut season
	$seasondebutsql = 'SELECT O.guid, O.post_title FROM '.$wpdb->prefix.'match_team T, '.$wpdb->prefix.'match M, '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'season S, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' O WHERE S.sea_id = J.tid AND J.prefix = \'sea_\' AND J.pid = O.ID AND C.sea_id = S.sea_id AND C.c_id = M.c_id AND C.c_show = 1 AND C.c_counts = 1 AND M.m_id = T.m_id AND T.t_id = '.$tid.' ORDER BY M.m_date ASC LIMIT 1';
	$sd = $wpdb->get_row($seasondebutsql);
?>

	<div id="subcontent">
		<ul>
			<li class="sidelogo"><?php print($timg); ?></li>
			<li class="sideinfo"><h2>Team Information</h2>
			  <ul>
			   <li><strong>Status:</strong> <?php if ($ti->t_active) { print("Active"); } else { print("Disbanded"); } ?></li>
			   <li><strong>Team Value:</strong> <?php print(number_format($ti->t_tv)); ?>gp</li>
			   <li><strong>Current Form:</strong> <?php print($currentform); ?></li>
			   <li><strong>Head Coach:</strong> <?php print($ti->t_hcoach); ?></li>
<?php
		if (isset($teamcaplink)) {
?>
			   <li><strong>Current Captain:</strong> <?php print($teamcaplink); ?></li>
<?php
		}
?>
			   <li><strong>Team Owner:</strong> <?php print($ti->display_name); ?></li>
			   <li><strong>Stadium:</strong> <a href="<?php print($ti->stadlink); ?>" title="Learn more about <?php print($ti->stad); ?>"><?php print($ti->stad); ?></a></li>
			   <li><strong>Debut:</strong> <a href="<?php print($sd->guid); ?>" title="Read more on <?php print($sd->post_title); ?>"><?php print($sd->post_title); ?></a></li>
			   <li><strong>Race:</strong> <a href="<?php print($ti->racelink); ?>" title="Read more about <?php print($ti->r_name); ?> teams"><?php print($ti->r_name); ?></a></li>
			  </ul>
<?
				if ($ti->t_roster) {
					print("			  <ul>\n			   <li><a href=\"".$rosterlink."/\" title=\"View the teams full roster \">View Full Roster &gt;&gt;</a></li>\n			  </ul>\n");
				}
?>
			</li>
<?php
			if ($has_played) {
?>
			<li class="sideawards"><h2>Championships</h2>
<?php
		if ($has_cups) {
			print("<ul>\n");
			foreach ($champs as $cc) {
				print("	<li><strong>".$cc->a_name."</strong> - <a href=\"".$cc->guid."\" title=\"View full details about ".$cc->post_title."\">".$cc->post_title."</a></li>\n");
			}
			print("</ul>\n");
		}
		else {
			print("<p>This team has not won any Championships at present.</p>\n");
		}
		print("<p><a href=\"#awardsfull\" title=\"View all awards this team has won\">View all awards this team has won &gt;&gt;</a></p>");
?>
			</li>

			<li><h2>Currently Participating in</h2>
<?php
			$currentcompssql = 'SELECT O.post_title, O.guid from '.$wpdb->prefix.'team T, '.$wpdb->prefix.'team_comp M, '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' O WHERE C.c_id = J.tid AND J.prefix = \'c_\' AND J.pid = O.ID AND M.c_id = C.c_id AND C.c_counts = 1 AND C.c_show = 1 AND C.c_active = 1 AND T.t_id = M.t_id AND T.t_id = '.$tid.' GROUP BY C.c_id LIMIT 0, 30 ';
			if ($currentcomp = $wpdb->get_results($currentcompssql)) {
				print("				<ul>\n");
				foreach ($currentcomp as $curc) {
					print("					<li><a href=\"".$curc->guid."\" title=\"Read more about ".$curc->post_title."\">".$curc->post_title."</a></li>\n");
				}
				print("				</ul>\n");
			}
			else {
				print("<p>This team is currently not taking part in any Competitions.</p>\n");
			}
			print("			</li>\n");


			$topplayerssql = 'SELECT P.post_title, P.guid, T.p_spp FROM '.$wpdb->prefix.'player T, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE T.p_id = J.tid AND J.prefix = \'p_\' AND J.pid = P.ID AND T.t_id = '.$tid.' ORDER BY T.p_spp DESC LIMIT 5';
			if ($topp = $wpdb->get_results($topplayerssql)) {
				print("<li>\n	<h2>Top Players on this team</h2>\n	<ul>\n");
				foreach ($topp as $tp) {
					print("	<li><a href=\"".$tp->guid."\" title=\"Read more about ".$tp->post_title."\">".$tp->post_title."</a> - ".$tp->p_spp."</li>\n");
				}
				print("</ul>\n</li>\n");
			}
		}//end of if $has_played


			$otherteamssql = 'SELECT P.post_title, P.guid FROM '.$wpdb->prefix.'team T, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE T.t_id = J.tid AND J.prefix = \'t_\' AND J.pid = P.ID AND T.t_show = 1 AND T.t_id != '.$tid.' AND T.type_id = 1 ORDER BY RAND() LIMIT 5';
			if ($oteam = $wpdb->get_results($otherteamssql)) {
				print("<li>\n	<h2>Other Teams in the HDWSBBL</h2>\n	<ul>\n");
				foreach ($oteam as $ot) {
					print("	<li><a href=\"".$ot->guid."\" title=\"Read more about ".$ot->post_title."\">".$ot->post_title."</a></li>\n");
				}
				print("</ul>\n</li>\n");
			}

if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-common') ) : ?>
			<li><h2 class="widgettitle">Opps</h2>
			  <ul>
			   <li>Something has gone wrong and you have lost your widget settings. better log in quick and fix it!</li>
			  </ul>
			</li>
<?php endif;
?>

		</ul>
	</div><!-- end of #subcontent -->
<?php get_footer(); ?>