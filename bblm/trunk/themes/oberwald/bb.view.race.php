<?php
/*
Template Name: View Race
*/
/*
*	Filename: bb.view.race.php
*	Description: Page template to view the details of a race.
*/
?>
<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<div class="entry">
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h2 class="entry-title"><?php the_title(); ?></h2>

					<div class="details racedet">
						<?php the_content(); ?>
					</div>
<?php
				$racesql = "SELECT P.*, R.r_rrcost, R.r_id FROM ".$wpdb->prefix."race R, ".$wpdb->posts." P, ".$wpdb->prefix."bb2wp J WHERE R.r_id = J.tid AND P.ID = J.pid AND P.ID = ".$post->ID;
				if ($races = $wpdb->get_results($racesql)) {
					print("<ul\n>");
					foreach ($races as $race) {
						print("	<li><strong>Re-Roll Cost</strong>: ".number_format($race->r_rrcost)."gp</li>\n");
						$race_id = $race->r_id;
					}
					print("</ul>\n");
				}
				else {
					print("	<ul>\n	<li><strong>Re-Roll Cost</strong>: Not Available</li>\n</ul>\n");
				}

				if (isset($race_id)) {
					//we only want to continue if the above selection returned something.
					print("<h3>Positions available for Race</h3>\n");
					//Grab Positions
					$positionsql = 'SELECT * FROM '.$wpdb->prefix.'position WHERE pos_status = 1 AND r_id = '.$race_id.' ORDER by pos_cost ASC';
					if ($positions = $wpdb->get_results($positionsql)) {
						$zebracount = 1;
						print("<table>\n	<tr>\n		<th>Name</th>\n		<th>Limit</th>\n		<th class=\"tbl_stat\">MA</th>\n		<th class=\"tbl_stat\">ST</th>\n		<th class=\"tbl_stat\">AG</th>\n		<th class=\"tbl_stat\">AV</th>\n		<th>Skills</th>\n		<th>Cost</th>\n	</tr>\n");
						foreach ($positions as $pos) {
							if ($zebracount % 2) {
								print("		<tr id=\"pos-".$pos->pos_id."\">\n");
							}
							else {
								print("	<tr class=\"tbl_alt\" id=\"pos-".$pos->pos_id."\">\n");
							}
							print("		<td>".$pos->pos_name."</td>\n		<td>0 - ".$pos->pos_limit."</td>\n		<td>".$pos->pos_ma."</td>\n		<td>".$pos->pos_st."</td>\n		<td>".$pos->pos_ag."</td>\n		<td>".$pos->pos_av."</td>\n		<td class=\"tbl_skills\">".$pos->pos_skills."</td>\n		<td>".number_format($pos->pos_cost)."gp</td>\n	</tr>\n");
							$zebracount++;
						}
						print("</table>\n");
					}
					else {
						print("	<div class=\"info\">\n		<p>Sorry, but no positions have been filled out for this race</p>\n	</div>\n");
					}

					//Availible Star Players
					$starplayersql = 'SELECT P.post_title, P.guid, X.p_ma, X.p_st, X.p_ag, X.p_av, X.p_skills, X.p_cost FROM '.$wpdb->prefix.'race2star S, '.$wpdb->prefix.'posts P, '.$wpdb->prefix.'bb2wp J, '.$wpdb->prefix.'player X WHERE J.pid = P.ID AND J.prefix = \'p_\' AND J.tid = S.p_id AND X.p_id = S.p_id AND S.r_id = '.$race_id.' AND X.p_status = 1 ORDER BY P.post_title ASC LIMIT 0, 30 ';
					if ($starplayer = $wpdb->get_results($starplayersql)) {
						$zebracount = 1;
						print("<h3>Star Players available for Race</h3>\n");
						print("<table>\n	<tr>\n		<th>Name</th>\n		<th class=\"tbl_stat\">MA</th>\n		<th class=\"tbl_stat\">ST</th>\n		<th class=\"tbl_stat\">AG</th>\n		<th class=\"tbl_stat\">AV</th>\n		<th>Skills</th>\n		<th>Cost</th>\n	</tr>\n");
						foreach ($starplayer as $star) {
							if ($zebracount % 2) {
								print("		<tr>\n");
							}
							else {
								print("	<tr class=\"tbl_alt\">\n");
							}
							print("		<td><a href=\"".$star->guid."\" title=\"See more details of this player\">".$star->post_title."</a></td>\n		<td>".$star->p_ma."</td>\n		<td>".$star->p_st."</td>\n		<td>".$star->p_ag."</td>\n		<td>".$star->p_av."</td>\n		<td class=\"tbl_skills\">".$star->p_skills."</td>\n		<td>".number_format($star->p_cost)."gp</td>\n	</tr>\n");
							$zebracount++;
						}
						print("</table>\n");
					}


					print("<h3>Teams belonging to this Race</h3>\n");
					$teamsql = 'SELECT T.t_name, P.guid FROM '.$wpdb->prefix.'team T, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE T.t_id = J.tid AND J.prefix = \'t_\' AND J.pid = P.ID AND T.t_show = 1 AND T.r_id = '.$race_id.' ORDER by T.t_name ASC';
					if ($teams = $wpdb->get_results($teamsql)) {
						print("<ul>\n");
						foreach ($teams as $td) {
							print("<li><a href=\"".$td->guid."\" title=\"View more details of this team\">".$td->t_name."</a></li>\n");
						}
						print("</ul>\n");
					}
					else {
						print("	<div class=\"info\">\n		<p>There are currently no teams representing this Race.</p>\n	</div>.\n");
					}
				}
				else {
					print("	<div class=\"info\">\n		<p>There are currently no details set up for this Race.</p>\n </div>\n");
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