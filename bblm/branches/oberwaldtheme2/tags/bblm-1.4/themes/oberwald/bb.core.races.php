<?php
/*
Template Name: Race Listing
*/
/*
*	Filename: bb.core.races.php
*	Version: 1.1
*	Description: Page template to list the races in the league
*/
/* -- Change History --
20080218 - 0.0a - Intital creation of file. a quick and dirty solution.
20080219 - 0.1a - addmendment of links to be permalinks and not generated ones.
20080226 - 0.1.1a - Moved into a new directory sturucture.
20080415 - 1.0b - re-write from the ground up. simple list of races that have been created.
20080613 - 1,1b - Added breadbrumbs to the page
20080730 - 1.0 - bump to Version 1 for public release.
20090712 - 1.0.1 - Added DKY code to page
20100123 - 1.1 - Updated the prefix for the custom bb tables in the Database (tracker [225])

*/
?>
<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		<div id="breadcrumb">
			<p><a href="<?php echo get_option('home'); ?>" title="Back to the front of the HDWSBBL">HDWSBBL</a> &raquo; Races</p>
		</div>

			<div class="entry">
				<h2><?php the_title(); ?></h2>
				<?php the_content('Read the rest of this entry &raquo;'); ?>
<?php
				$racesql = "SELECT P.post_title, P.guid FROM ".$wpdb->prefix."race R, ".$wpdb->posts." P, ".$wpdb->prefix."bb2wp J WHERE R.r_id = J.tid AND P.ID = J.pid and J.prefix = 'r_' ORDER BY P.post_title ASC";
				if ($races = $wpdb->get_results($racesql)) {
					print("<ul>\n");
					foreach ($races as $race) {
						print("	<li><a href=\"".$race->guid."\" title=\"View more informaton about ".$race->post_title."\">".$race->post_title."</a></li>\n");
					}
					print("</ul>\n");
				}
				else {
					print("	<div class=\"info\">\n		<p>There are no races currently set-up</p>\n	</div>\n");
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



