<?php
/*
Template Name: List Cups
*/
/*
*	Filename: bb.core.cup.php
*	Description: Page template to display the championship cups
*/
?>
<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		<div id="breadcrumb">
			<p><a href="<?php echo home_url(); ?>" title="Back to the front of the HDWSBBL">HDWSBBL</a> &raquo; Championship Cups</p>
		</div>
			<div class="entry">
				<h2><?php the_title(); ?></h2>

				<?php the_content('Read the rest of this entry &raquo;'); ?>
<?php
			$seriessql = 'SELECT P.post_title, P.guid, COUNT(*) AS scount FROM '.$wpdb->prefix.'series S, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P, '.$wpdb->prefix.'comp C WHERE C.c_counts = 1 AND C.c_show = 1 AND C.type_id = 1 AND C.series_id = S.series_id AND S.series_id = J.tid AND J.prefix = \'series_\' AND J.pid = P.ID AND S.series_show = 1 GROUP BY S.series_id ORDER BY S.series_id ASC';
			if ($cups = $wpdb->get_results($seriessql)) {
				$zebracount = 1;
				print("<table>\n	<tr>\n		<th>Cup Title</th>\n		<th>Competitions</th>\n	</tr>");
				foreach ($cups as $c) {
					if ($zebracount % 2) {
						print("	<tr>\n");
					}
					else {
						print("	<tr class=\"tbl_alt\">\n");
					}
					print("		<td><a href=\"".$c->guid."\" title=\"View more information on ".$c->post_title."\">".$c->post_title."</a></td>\n		<td>".$c->scount."</td>\n	</tr>");
					$zebracount++;
				}
				print("</table>\n");
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