<?php get_header(); ?>
	<?php if (have_posts()) : ?>

	<h2><?php if ( is_day() ) : ?>
				<?php printf( __( 'Daily Archives: <span>%s</span>', 'bblm' ), get_the_date() ); ?>
<?php elseif ( is_month() ) : ?>
				<?php printf( __( 'Monthly Archives: <span>%s</span>', 'bblm' ), get_the_date('F Y') ); ?>
<?php elseif ( is_year() ) : ?>
				<?php printf( __( 'Yearly Archives: <span>%s</span>', 'bblm' ), get_the_date('Y') ); ?>
<?php else : ?>
				<?php _e( 'Blog Archives', 'bblm' ); ?>
<?php endif; ?></h2>
<?php rewind_posts();?>
		<?php while (have_posts()) : the_post(); ?>
			<div class="entry">
				<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<p class="postdate"><?php the_time('F jS, Y') ?> <!-- by <?php the_author(); ?> --></p>

				<?php the_content('Read the rest of this entry &raquo;'); ?>

				<p class="postmeta">Originally posted in <?php the_category(',') ?> <strong>|</strong> <?php edit_post_link('Edit', ' <strong>[</strong> ', ' <strong>]</strong> '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>

			</div>


		<?php endwhile; else: ?>
			<p><?php _e('Sorry, There where no items posted during this time period.','bblm' ); ?></p>
		<?php endif; ?>



		<ul class="subnav">
			<?php next_posts_link('<li>&laquo; Previous Entries</li>') ?>
			<?php previous_posts_link('<li>Next Entries &raquo;</li>') ?>
		</ul>
<?php
		//Did You Know Display Code
		if (function_exists(bblm_display_dyk)) {
			bblm_display_dyk();
		}
?>


<?php get_sidebar(); ?>
<?php get_footer(); ?>