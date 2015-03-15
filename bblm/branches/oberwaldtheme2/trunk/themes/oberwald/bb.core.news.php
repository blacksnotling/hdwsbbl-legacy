<?php
/*
Template Name: News Front Page
*/
/*
*	Filename: bb.core.news.php
*	Description: The Template for the front page of the news section
*/
$options = get_option('bblm_config');
$bblm_league_name = htmlspecialchars($options['league_name'], ENT_QUOTES);
if ( strlen($bblm_league_name) < 1) {
	$bblm_league_name = "league";
}
?>
<?php get_header(); ?>
	<?php if (have_posts()) : ?>

		<h2>Latest News</h2>
		<div id="breadcrumb">
			<p><a href="<?php echo home_url(); ?>" title="Back to the front of the <?php print ($bblm_league_name); ?>"><?php print ($bblm_league_name); ?></a> &raquo; News</p>
		</div>
		<ul class="subnav">
			<li><a href="<?php echo home_url(); ?>/page/2/" title="View Previous News Items">&laquo; Previous Entries</a></li>
		</ul>


<?php
	    $recentPosts = new WP_Query();
	    $recentPosts->query('cat=-13&showposts=6');
?>
		<?php while ($recentPosts->have_posts()) : $recentPosts->the_post(); ?>
			<div class="entry">
				<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<p class="postdate"><?php the_time('F jS, Y') ?> <!-- by <?php the_author(); ?> --></p>

				<?php the_content('Read the rest of this entry &raquo;'); ?>

				<p class="postmeta">Posted in <?php the_category(',') ?> <strong>|</strong> <?php edit_post_link('Edit', ' <strong>[</strong> ', ' <strong>]</strong> '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>

			</div>


		<?php endwhile; ?>
		<?php endif; ?>


		<ul class="subnav">
			<li><a href="<?php echo home_url(); ?>/page/2/" title="View Previous News Items">&laquo; Previous Entries</a></li>
		</ul>
<?php
		//Did You Know Display Code
		if (function_exists(bblm_display_dyk)) {
			bblm_display_dyk();
		}
?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>