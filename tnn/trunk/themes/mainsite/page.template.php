<?php
/*
Template Name: X.X
*/
/*
*	Filename: bb.X.X.php
*	Version: 0.0b
*	Description: .Page template to display
*/
/* -- Change History --
20080413 - 1.0b - Initial creation of file.

*/
?>
<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		<div id="breadcrumb">
			<p><a href="<?php echo get_option('home'); ?>" title="Back to the front of the HDWSBBL Team News Network">Team News Network</a> &raquo; <?php the_title(); ?></p>
		</div>
			<div class="entry">
				<h2><?php the_title(); ?></h2>

				<?php the_content('Read the rest of this entry &raquo;'); ?>

				<p class="postmeta"><?php edit_post_link('Edit', ' <strong>[</strong> ', ' <strong>]</strong> '); ?></p>

			</div>


		<?php endwhile;?>
	<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>