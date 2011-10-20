<?php
/*
Template Name: View Star Player
*/
/*
*	Filename: bb.view.starplayer.php
*	Description: .Page to display a Star Player.
*/
?>
<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		<?php
			/*
			Gather Information for page
			*/
			$playersql = 'SELECT P.p_id, P.t_id, P.p_ma, P.p_st, P.p_ag, P.p_av, P.p_spp, P.p_skills, P.p_cost FROM '.$wpdb->prefix.'player P, '.$wpdb->prefix.'bb2wp J WHERE J.tid = P.p_id AND J.prefix = \'p_\' AND J.pid = '.$post->ID;
			$pd = $wpdb->get_row($playersql);
?>
		<div class="entry">
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h2 class="entry-title"><?php the_title(); ?></h2>
			<div class="details">
				<?php the_content(); ?>
			</div>
			<table>
				<tr>
					<th class="tbl_name">Position</th>
					<th class="tbl_stat">MA</th>
					<th class="tbl_stat">ST</th>
					<th class="tbl_stat">AG</th>
					<th class="tbl_stat">AV</th>
					<th>Skills</th>
					<th>Cost per match</th>
				</tr>
				<tr>
					<td>Star Player</td>
					<td><?php echo $pd->p_ma; ?></td>
					<td><?php echo $pd->p_st; ?></td>
					<td><?php echo $pd->p_ag; ?></td>
					<td><?php echo $pd->p_av; ?></td>
					<td class="tbl_skills"><?php  echo $pd->p_skills; ?></td>
					<td><?php  echo number_format($pd->p_cost); ?>gp</td>
				</tr>
			</table>
<?php
		$racelistsql = 'SELECT P.post_title, P.guid FROM '.$wpdb->prefix.'race2star R, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE P.ID = J.pid AND J.prefix = "r_" AND J.tid = R.r_id AND R.p_id = '.$pd->p_id.' ORDER BY P.post_title ASC';
		$racelist = $wpdb->get_results($racelistsql);

		$is_first = 1;
		echo '<p>Availible to hire for the following Races:';
		foreach ($racelist as $rl) {
			if (! $is_first) {
				echo ',';
			}

			echo ' <a href="'.$rl->guid.'" title="View more about '.$rl->post_title.' Blood Bowl Teams">'.$rl->post_title.'</a>';
			$is_first = 0;
		}
		echo ".</p>\n";


		//Career Stats
		$careerstatssql = 'SELECT COUNT(*) AS GAMES, SUM(M.mp_td) AS TD, SUM(M.mp_cas) AS CAS, SUM(M.mp_comp) AS COMP, SUM(M.mp_int) AS MINT, SUM(M.mp_mvp) AS MVP, SUM(M.mp_spp) AS SPP, T.t_name AS post_title FROM '.$wpdb->prefix.'match_player M, '.$wpdb->prefix.'team T WHERE M.t_id = T.t_id AND M.mp_counts = 1 AND M.p_id = '.$pd->p_id.' GROUP BY M.p_id ORDER BY T.t_name ASC';
		if ($s = $wpdb->get_row($careerstatssql)) {
			//The Star has played a match so continue
?>
			<h3>HDWSBBL Statistics</h3>
			<table>
				<tr>
					<th class="tbl_title">Career Total</th>
					<th class="tbl_stat">Pld</th>
					<th class="tbl_stat">TD</th>
					<th class="tbl_stat">CAS</th>
					<th class="tbl_stat">COMP</th>
					<th class="tbl_stat">INT</th>
					<th class="tbl_stat">MVP</th>
					<th class="tbl_stat">SPP</th>
				</tr>
				<tr>
					<td><?php the_title(); ?></th>
					<td><?php echo $s->GAMES; ?></th>
					<td><?php echo $s->TD; ?></th>
					<td><?php echo $s->CAS; ?></th>
					<td><?php echo $s->COMP; ?></th>
					<td><?php echo $s->MINT; ?></th>
					<td><?php echo $s->MVP; ?></th>
					<td><?php echo $s->SPP; ?></th>
				</tr>
			</table>
<?php

			//Breakdown by team
			$statssql = 'SELECT COUNT(*) AS GAMES, SUM(M.mp_td) AS TD, SUM(M.mp_cas) AS CAS, SUM(M.mp_comp) AS COMP, SUM(M.mp_int) AS MINT, SUM(M.mp_mvp) AS MVP, SUM(M.mp_spp) AS SPP, T.t_name AS post_title, T.t_guid AS guid FROM '.$wpdb->prefix.'match_player M, '.$wpdb->prefix.'team T WHERE M.t_id = T.t_id AND M.mp_counts = 1 AND M.p_id = '.$pd->p_id.' GROUP BY T.t_id ORDER BY GAMES DESC, T.t_name ASC';
			if ($stats = $wpdb->get_results($statssql)) {
				$zebracount = 1;
?>

			<table>
				<tr>
					<th class="tbl_title">Playing for</th>
					<th class="tbl_stat">Pld</th>
					<th class="tbl_stat">TD</th>
					<th class="tbl_stat">CAS</th>
					<th class="tbl_stat">COMP</th>
					<th class="tbl_stat">INT</th>
					<th class="tbl_stat">MVP</th>
					<th class="tbl_stat">SPP</th>
				</tr>

<?php
				foreach ($stats as $s) {
					if ($zebracount % 2) {
						print("				<tr>\n");
					}
					else {
						print("				<tr class=\"tbl_alt\">\n");
					}
					print ("					<td><a href=\"".$s->guid."\" title=\"Read more about ".$s->post_title."\">".$s->post_title."</a></td>\n					<td>".$s->GAMES."</td>\n					<td>".$s->TD."</td>\n					<td>".$s->CAS."</td>\n					<td>".$s->COMP."</td>\n					<td>".$s->MINT."</td>\n					<td>".$s->MVP."</td>\n					<td>".$s->SPP."</td>\n				</tr>\n");
					$zebracount++;
				}
				print("			</table>\n");
			}

			// -- KILLER --
			$killersql = 'SELECT O.post_title AS PLAYER, O.guid AS PLAYERLink, T.t_name AS TEAM, T.t_guid AS TEAMLink, X.pos_name FROM '.$wpdb->prefix.'player_fate F, '.$wpdb->prefix.'player P, '.$wpdb->prefix.'team T, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' O, '.$wpdb->prefix.'position X WHERE F.p_id = P.p_id AND P.t_id = T.t_id AND P.pos_id = X.pos_id AND F.p_id = J.tid AND J.prefix = \'p_\' AND J.pid = O.ID AND F.pf_killer = '.$pd->p_id.' AND F.p_id != '.$pd->p_id.' ORDER BY F.m_id ASC';
			if ($killer = $wpdb->get_results($killersql)) {
				//If the player has killed people
?>
			<h3>Killer!</h3>
			<p>This player has killed another player in the course of their career. They have killed the following players:</p>
			<ul>
<?php
				foreach ($killer as $k) {
					print ("				<li><a href=\"".$k->PLAYERLink."\" title=\"Read more about ".$k->PLAYER."\">".$k->PLAYER."</a> (".$k->pos_name." for <a href=\"".$k->TEAMLink."\" title=\"Read more about ".$k->TEAM."\">".$k->TEAM."</a>)</li>\n");
				}
?>
			</ul>
<?php
			}

?>
			<h3>Breakdown by Competition</h3>
			<table>
				<tr>
					<th class="tbl_title">Competition</th>
					<th class="tbl_stat">Pld</th>
					<th class="tbl_stat">TD</th>
					<th class="tbl_stat">CAS</th>
					<th class="tbl_stat">INT</th>
					<th class="tbl_stat">COMP</th>
					<th class="tbl_stat">MVP</th>
					<th class="tbl_stat">SPP</th>
				</tr>
<?php
			$playercompsql = 'SELECT COUNT(*) AS GAMES, SUM(M.mp_td) AS TD, SUM(M.mp_cas) AS CAS, SUM(M.mp_comp) AS COMP, SUM(M.mp_int) AS MINT, SUM(M.mp_mvp) AS MVP, SUM(M.mp_spp) AS SPP, S.guid, S.post_title FROM '.$wpdb->prefix.'match_player M, '.$wpdb->prefix.'player P, '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'match Q, '.$wpdb->prefix.'bb2wp R, '.$wpdb->posts.' S WHERE C.c_id = R.tid AND R.pid = S.ID AND R.prefix = \'c_\' AND M.m_id = Q.m_id AND Q.c_id = C.c_id AND C.c_counts = 1 AND C.c_show = 1 AND M.p_id = P.p_id AND M.p_id = '.$pd->p_id.' GROUP BY C.c_id ORDER BY C.c_id DESC';
			if ($playercomp = $wpdb->get_results($playercompsql)) {
				$zebracount = 1;
				foreach ($playercomp as $pc) {
					if ($zebracount % 2) {
						print("				<tr>\n");
					}
					else {
						print("				<tr class=\"tbl_alt\">\n");
					}
					print("				<td><a href=\"".$pc->guid."\" title=\"View more details about this competition\">".$pc->post_title."</a></td>\n					<td>".$pc->GAMES."</td>\n					<td>".$pc->TD."</td>\n					<td>".$pc->CAS."</td>\n					<td>".$pc->MINT."</td>\n					<td>".$pc->COMP."</td>\n					<td>".$pc->MVP."</td>\n					<td>".$pc->SPP."</td>\n				</tr>\n");
					$zebracount++;
				}
			}
			print("			</table>\n");
?>

			<h3>Breakdown by Season</h3>
			<table>
				<tr>
					<th class="tbl_title">Season</th>
					<th class="tbl_stat">P</th>
					<th class="tbl_stat">TD</th>
					<th class="tbl_stat">CAS</th>
					<th class="tbl_stat">INT</th>
					<th class="tbl_stat">COMP</th>
					<th class="tbl_stat">MVP</th>
					<th class="tbl_stat">SPP</th>
				</tr>
<?php
					$playerseasql = 'SELECT S.post_title, S.guid, COUNT(*) AS GAMES, SUM(M.mp_td) AS TD, SUM(M.mp_cas) AS CAS, SUM(M.mp_comp) AS COMP, SUM(M.mp_int) AS MINT, SUM(M.mp_mvp) AS MVP, SUM(M.mp_spp) AS SPP FROM '.$wpdb->prefix.'season X, '.$wpdb->prefix.'match_player M, '.$wpdb->prefix.'player P, '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'match Q, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' S WHERE C.sea_id = J.tid AND J.prefix = \'sea_\' AND J.pid = S.ID AND C.c_counts = 1 AND C.c_show = 1 AND X.sea_id = C.sea_id AND M.m_id = Q.m_id AND Q.c_id = C.c_id AND M.p_id = P.p_id AND M.p_id = '.$pd->p_id.' GROUP BY C.sea_id ORDER BY C.sea_id DESC';
					if ($playersea = $wpdb->get_results($playerseasql)) {
					$zebracount = 1;
						foreach ($playersea as $pc) {
							if ($zebracount % 2) {
								print("				<tr>\n");
							}
							else {
								print("				<tr class=\"tbl_alt\">\n");
							}
							print("					<td><a href=\"".$pc->guid."\" title=\"View more information about this Season\">".$pc->post_title."</a></td>\n					<td>".$pc->GAMES."</td>\n					<td>".$pc->TD."</td>\n					<td>".$pc->CAS."</td>\n					<td>".$pc->MINT."</td>\n					<td>".$pc->COMP."</td>\n					<td>".$pc->MVP."</td>\n					<td>".$pc->SPP."</td>\n				</tr>\n");
							$zebracount++;
						}
					}
					print("			</table>\n");
?>

			<h3>Recent Matches</h3>
			<table class="sortable expandable">
				<thead>
				<tr>
					<th>Date</th>
					<th>For</th>
					<th>Against</th>
					<th>TD</th>
					<th>CAS</th>
					<th>INT</th>
					<th>COMP</th>
					<th>MVP</th>
					<th>SPP</th>
				</tr>
				</thead>
				<tbody>
<?php
			$playermatchsql = 'SELECT M.*, P.p_name, UNIX_TIMESTAMP(X.m_date) AS mdate, G.post_title AS TA, T.t_id AS TAid, G.guid AS TAlink, B.post_title AS TB, B.guid AS TBlink, R.t_id AS TBid, Z.guid FROM '.$wpdb->prefix.'match_player M, '.$wpdb->prefix.'player P, '.$wpdb->prefix.'match X, '.$wpdb->prefix.'bb2wp Y, '.$wpdb->posts.' Z, '.$wpdb->prefix.'team T, '.$wpdb->prefix.'team R, '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'bb2wp F, '.$wpdb->posts.' G, '.$wpdb->prefix.'bb2wp V, '.$wpdb->posts.' B WHERE T.t_id = F.tid AND F.prefix = \'t_\' AND F.pid = G.ID AND R.t_id = V.tid AND V.prefix = \'t_\' AND V.pid = B.ID AND C.c_id = X.c_id AND C.c_counts = 1 AND C.c_show = 1 AND X.m_teamA = T.t_id AND X.m_teamB = R.t_id AND M.p_id = P.p_id AND M.m_id = X.m_id AND X.m_id = Y.tid AND Y.prefix = \'m_\' AND Y.pid = Z.ID AND M.p_id = '.$pd->p_id.' ORDER BY X.m_date DESC';
			if ($playermatch = $wpdb->get_results($playermatchsql)) {
			$zebracount = 1;
				foreach ($playermatch as $pm) {
					if (($zebracount % 2) && (10 < $zebracount)) {
						print("				<tr class=\"tb_hide\">\n");
					}
					else if (($zebracount % 2) && (10 >= $zebracount)) {
						print("				<tr>\n");
					}
					else if (10 < $zebracount) {
						print("				<tr class=\"tbl_alt tb_hide\">\n");
					}
					else {
						print("				<tr class=\"tbl_alt\">\n");
					}
					print("					<td><a href=\"".$pm->guid."\" title=\"View the match in more detail\">".date("d.m.y", $pm->mdate)."</a></td>\n");
					if ($pm->TAid == $pm->t_id) {
						print("					<td><a href=\"".$pm->TAlink."\" title=\"Read up on this team\">".$pm->TA."</a></td>\n					<td><a href=\"".$pm->TBlink."\" title=\"Read up on the opponants\">".$pm->TB."</a></td>\n");
					}
					else {
						print("					<td><a href=\"".$pm->TBlink."\" title=\"Read up on this team\">".$pm->TB."</a></td>\n					<td><a href=\"".$pm->TAlink."\" title=\"Read up on the opponants\">".$pm->TA."</a></td>\n");
					}
					print("					<td>");
					if (0 == $pm->mp_td) {
						print("0");
					}
					else {
						print("<strong>".$pm->mp_td."</strong>");
					}
					print("</td>\n					<td>");
					if (0 == $pm->mp_cas) {
						print("0");
					}
					else {
						print("<strong>".$pm->mp_cas."</strong>");
					}
					print("</td>\n					<td>");
					if (0 == $pm->mp_int) {
						print("0");
					}
					else {
						print("<strong>".$pm->mp_int."</strong>");
					}
					print("</td>\n					<td>");
					if (0 == $pm->mp_comp) {
						print("0");
					}
					else {
						print("<strong>".$pm->mp_comp."</strong>");
					}
					print("</td>\n					<td>");
					if (0 == $pm->mp_mvp) {
						print("0");
					}
					else {
						print("<strong>".$pm->mp_mvp."</strong>");
					}
					print("</td>\n					<td>");
					if (0 == $pm->mp_spp) {
						print("0");
					}
					else {
						print("<strong>".$pm->mp_spp."</strong>");
					}
					print("</td>\n");
					$zebracount++;
				}
				print("				</tbody>\n			</table>\n");
			}




		}//End of if a player has played a match
		else {
			//Star has not made debut yet
			print("					<div class=\"info\">\n						<p>This Star Player has not made their Debut yet. Stay tuned for further developments.</p>\n					</div>\n");
		}
?>
					<?php get_sidebar('entry'); ?>

					<p class="postmeta"><?php edit_post_link('Edit', ' <strong>[</strong> ', ' <strong>]</strong> '); ?></p>

				</div>
			</div>


		<?php endwhile;?>
	<?php endif; ?>

<?php get_sidebar('content'); ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>