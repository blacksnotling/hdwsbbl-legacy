<?php
/*
Template Name: GraveYard
*/
/*
*	Filename: bb.core.graveyard.php
*	Version: 1.1
*	Description: .Page template to display
*/
/* -- Change History --
20090820 - 1.0b - Initial creation of file.
20090921 - 1.0 - Completion of file. I got the Eagle Award to Show
20100123 - 1.1 - Updated the prefix for the custom bb tables in the Database (tracker [225])

//Some Config -- Not working at present!
$bblm_league_type = '1'; //LRB
$bblm_eagle = 16; //In the event I ever change it, this is the ID in the database.


*/
?>
<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		<div id="breadcrumb">
			<p><a href="<?php echo get_option('home'); ?>" title="Back to the front of the HDWSBBL">HDWSBBL</a> &raquo; <?php the_title(); ?></p>
		</div>
			<div class="entry">
				<h2><?php the_title(); ?></h2>

				<?php the_content('Read the rest of this entry &raquo;'); ?>
<?php
		//SQL to determine the Eagle Award WInners. This will be used to highlight the 'winners'
		$eaglesql = 'SELECT p_id FROM `'.$wpdb->prefix.'awards_player_sea` WHERE a_id = 16';
		if ($eagles = $wpdb->get_results($eaglesql, ARRAY_N)) {
			$eagles_exist = 1;

			//Do not mind me
/*			print("<pre>");
			print_r($eagles);
			print("</pre>");*/
		}

		//Main SQL Query to determine the Dead
		$deadsql = 'SELECT P.p_id, T.t_name, K.post_title, K.guid, P.p_num, O.pos_name, T.t_guid, UNIX_TIMESTAMP(M.m_date) AS mdate, F.f_id FROM '.$wpdb->prefix.'player_fate F, '.$wpdb->prefix.'player P, '.$wpdb->prefix.'team T, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' K, '.$wpdb->prefix.'position O, '.$wpdb->prefix.'match M, '.$wpdb->prefix.'comp C WHERE (f_id = 1 OR f_id = 6) AND F.p_id = P.p_id AND P.t_id = T.t_id AND P.p_id = J.tid AND J.prefix = \'p_\' AND J.pid = K.ID AND P.pos_id = O.pos_id AND M.m_id = F.m_id AND M.c_id = C.c_id AND T.type_id = 1 AND C.c_counts = 1 AND C.c_show = 1 AND T.t_show = 1 ORDER BY T.t_name ASC, P.p_num ASC, K.post_title ASC';
		if ($dead = $wpdb->get_results($deadsql)) {
			$is_first = 1;
			$last_team = "";

			foreach ($dead as $d) {
				if ($d->t_name != $last_team) {
					if (1 != $is_first) {
						//If the team is not the first, and we are here, we close the div
						print("	</div><!-- end of .gycontainer -->\n");
					}
					else {
						//This is the first so no longer have it set
						$is_first = 0;
					}
						print("\n	<div class=\"gycontainer\">\n		<h3><a href=\"".$d->t_guid."\" title=\"Read more about ".$d->t_name."\">".$d->t_name."</a></h3>\n");
						$last_team = $d->t_name;
				}//end of if team does not match

?>
		<div class="gyplayer gyfate<?php print($d->f_id); ?>">
			<ul>
				<li><a href="<?php print($d->guid); ?>" title="See more on the career of <?php print($d->post_title); ?>"><?php print($d->post_title); ?></a> (#<?php print($d->p_num); ?> - <?php print($d->pos_name); ?>)</li>
				<li>Died: <?php print(date("d.m.25y", $d->mdate));?></li>
<?
			//If the player has won an eagle award, let everyone know
			//first we check something is in the eagles array
			if ($eagles_exist) {
				if (in_array_recursive($d->p_id, $eagles)) {
					print("				<li class=\"gyhighlight\">Eagle Award Winning Death!</li>\n");
				}
			}
?>
			</ul>
		</div><!-- end of .gyplayer -->
<?php

			}//end of foreach $dead
			print("	</div><!-- end of .gycontainer -->\n");
		}
		//nobody has died!
		else {
			print("	<p>Nobody has died!</p>\n");
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