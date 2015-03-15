<?php
/*
Template Name: Warzone Front Page
*/
/*
*	Filename: page.warzone
*	Description: The Template for the front page of the Warzone section
*/
$options = get_option('bblm_config');
$bblm_league_name = htmlspecialchars($options['league_name'], ENT_QUOTES);
if ( strlen($bblm_league_name) < 1) {
	$bblm_league_name = "league";
}
?>
<?php get_header(); ?>
	<?php query_posts('category_name=warzone'); ?>
	<?php if (have_posts()) : ?>

		<h2>The <?php print($bblm_league_name); ?>:WarZone</h2>

		<?php while (have_posts()) : the_post(); ?>
			<div class="entry">
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h2 class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
					<p class="postdate"><?php oberwald_posted_on() ?></p>

					<?php the_content(); ?>

					<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'bblm' ), 'after' => '</div>' ) ); ?>

					<p class="postmeta"><?php oberwald_posted_in() ?> <?php edit_post_link('Edit', ' <strong>[</strong> ', ' <strong>]</strong> '); ?> <?php oberwald_comments_link(); ?></p>
				</div>
			</div>


		<?php endwhile; else: ?>
					<p><?php _e('Sorry, no posts have been posted under this topic.', 'oberwald'); ?></p>
		<?php endif; ?>

<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<div id="nav-below" class="subnav">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&laquo;</span> Older Entries', 'bblm' ) ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( 'Newer Entries <span class="meta-nav">&raquo;</span>', 'bblm' ) ); ?></div>
				</div><!-- #nav-below -->
<?php endif; ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>