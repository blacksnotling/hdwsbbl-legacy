<?php
/*
Template Name: View Race
*/
/*
*	Filename: bb.view.race.php
*	Version: 1.2
*	Description: Page template to view a races details.
*/
/* -- Change History --
20080218 - 0.0a - Intital creation of file. a quick and dirty solution.
20080226 - 0.1a - Moved into a new directory sturucture.
20080329 - 0.1b - Added positions after it was created in DB. moved into Beta
20080415 - 1.0b - bit of a tidy up and converting into th new format. Also added basic list of teams for this race.
20080416 - 1.1b - added the details div around the content
20080521 - 1.2b - ensured that only the correct hdwsbbl teams where listed (IE t.show = 1).
20080613 - 1.3b -  added zebra striping and breadcrumbs to the page.
20080615 - 1.4b - Added Table classes and renamed template display name at top
20080707 - 1.4.1b - fixed a small validation error
20080730 - 1.0 - bump to Version 1 for public release.
20080805 - 1.1 - Formatted rr cost and restricted position SQL
20090712 - 1.1.1 - Added DYK code to page
20100123 - 1.2 - Updated the prefix for the custom bb tables in the Database (tracker [225])
20100831 - 1.2.1 - changed the sorting of the positions and added pos_id to the <tr>'s

*/
?>
<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		<div id="breadcrumb">
			<p><a href="<?php echo get_option('home'); ?>" title="Back to the front of the HDWSBBL">HDWSBBL</a> &raquo; <a href="<?php echo get_option('home'); ?>/races/" title="Back to the team listing">Races</a> &raquo; <?php the_title(); ?></p>
		</div>
			<div class="entry">
				<h2><?php the_title(); ?></h2>
				<div class="details racedet">
					<?php the_content('Read the rest of this entry &raquo;'); ?>
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
					$positionsql = 'SELECT * FROM '.$wpdb->prefix.'position WHERE pos_status = 1 AND r_id = '.$race_id.' ORDER by pos_limit DESC';
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

		//Did You Know Display Code
		if (function_exists(bblm_display_dyk)) {
			bblm_display_dyk();
		}
?>
				<p class="postmeta"><?php edit_post_link('Edit', ' <strong>[</strong> ', ' <strong>]</strong> '); ?></p>

			</div>


		<?php endwhile; ?>
	<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>