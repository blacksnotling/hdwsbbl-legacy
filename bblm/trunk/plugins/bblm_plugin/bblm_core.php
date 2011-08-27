<?php
/*
Plugin Name: Blood Bowl League Manager System (BBLM)
Plugin URI: http://www.hdwsbbl.co.uk/
Description: Everthing you need to run a Blood Bowl League via Wordpress!
Version: 1.3.1 (r1)
Author: Andrew Morris (Blacksnotling / Hexalon)
Author URI: http://www.blacksnotling.com/
*/
/*
/************ Change History *********
20071023 - V0.0 - creation of file.
20071023 - V0.1b - Added removal of Adin feeds.
20071023 - V0.2b - Added Custom Login Box.
20080218 - V0.3b - Added some holder files to the admin panel to get a feel for the layout..
20080227 - V0.3.1b - added the master "Blood Bowl" page to the admin scection along with add race
20080303 - V0.3.2b - Some more re-arranging of the admin layout.. Renamed this from a plugin to a System
20080304 - V0.5b - Integrated the Add Race Page in. Created the bblm Options page.
20080305 - V0.6b - Added "add Team" to the system and updated options page.
20080305 - V0.7b - Updated options page for page_series.
20080305 - V0.8b - updated the addteam and add race pages to take into account changed db schema.
20080307 - V0.9b - Added the "Add Series" page to the league.
20080310 - V0.10b - Added the "Add Comp" page to the league + updated options page
20080311 - V0.11b - Added the Assign teams to comp page.
20080312 - V0.12b - Begun work on add match record.
20080313 - V0.12.1b - add.match page up and running. only two bugs to squish
20080314 - V0.13b - fixes add.match bugs!!! Did some work on the recent matchs sidebar.
20080316 - V0.13.5b - changed add.match & add.series due to a change to the DB schema.
20080318 - V0.14b - Fixed an error in add.match
20080319 - V0.15b - Removed the major/minor switch from add.series. finished recent match widgit.
20080320 - V0.16b - Added the add.position funconality
20080320 - V0.16.1b - Began Work on Add.Player!
20080321 - V0.16.2b - more Work on Add.Player!
20080322 - V0.17b - completed Work on Add.Player!
20080322 - V0.18b - Added add/hire.journeyman (not to be confused with add player).
20080324 - V0.18.1b - Tydied up the hire.journeyman page
20080326 - V0.19b - Made some db changes to bb_player page and updated add.player as a result
20080326 - V0.19.1b - started work on add.match_player
20080331 - V0.20b - Completed add.match_player (to Alpha). Now I need to Crush some bugs!
20080401 - V0.20.1b - Crushed add.match_player bug.
20080402 - V0.21b - Edited options for stadium and created add.stadium. had to modify bb_team and add.team due to small change.
20080402 - V0.22b - edited add.team so home stadium can be selected, did varius other updates to the same page. corrected a bug on add. stadium
		   V0.23b - match result on add.match is automatically generated (no drop-downs). location of match can be selected
20080405 - V0.24b - went through all the admin files and looked for dev_ refrences rather the the correct $Rwpdb->
		   V0.24.1b - began work on delete.player
20080406 - V0.25b - Finished work on delete.player
20080407 - V0.26b - Overauled add.comp(after modifying db) and began (and completed!) work on add.award
		 - V0.26.1b - Began Work on end.comp
20080408 - V0.27b - Finished work on end.com and modified add.team to capture roster info.
20080409 - V0.28b - Removed dashboard crippler and custom login due to WordPress 2.5 upgrade.
20080414 - V0.29b - Added back custom login script.
20080417 - V0.30b - fixed the bug that calculated the TV wrong on add.player_match. added a new option and changed the default selection on add.player
20080419 - V0.31b - Fixed the bugg on delete.player where the match list was not getting generated properly
20080420 - V0.32b - Created add.season. Re-organised the core pages to include a new Matches One.
20080421 - V0.33b - created the bb_fixture table add.fixture. (+ modified appropiate plugin menus).
20080425 - V0.34b - modified add.comp fter modifying db schema, created add.comp_brackets.
20080426 - V0.34.1b - finished add.comp.brackets.
20080427 - V0.35b - modified add.match to take a fixture as input
20080428 - V0.36b - fixed the bug that recorded league points incorectly.
		 - V0.37b - Addad a new option for the default TBD team for fixture purposes
		 - V0.38b - Ammended add.comp_brackets to include links to teams (and escape the resulting text)
20080526 - V0.39b - Added Warzone Filtering
		 - V0.40b - Added the core of edit.match
20080531 - V0.41b - Streamlined the sql code for edit.match_trivia
				  - Began work on edit.match_comments but ran into issues with the initial sql string!
		 - V0.42b - Added Warzone cat and season parent page to the options page.
		 - V0.43b - Changed the page parent on add.season from stats to season.
		 - V0.44b - Changed the filter warone function to use the option from the db
20080602 - V0.45b - Modified add.award to reflect change in db (a_cup)
		 - V0.46b - Re-wrote end.comp from the ground up
		 - V0.47b - Created end.season and added it to the menu
20080615 - V0.48b - Added templating for Warzone Singles (from http://boren.nu/downloads/custom_post_templates.phps)
20080719 - V0.48.1b - modified add.comp so that the standings are not shown by default
		 - V0.49b - updated edit.match for coach comments and updated edit.match.comments
		 - V0.50b - added rename.player
20080722 - v0.50.1b - fixed rename.player!
20080723 - V0.51b - added edt.fixture
20080727 - V0.51.1b - Finished edit.fixture, added link to generate summary
20080728 - V0.51.2b - some more work on generate summary
20080729 - V0.52b - Finished generate Summary!
20080730 - 1.0 - bump to Version 1 for public release.
20080808 - 1.0 (r1) - Added links to edit.team and edit.player
20080822 - 1.0 (r2) - removed links to rename.player, delete.player and add.journeyman.
		 			  - added the link to the new jm report
20080823 - 1.0 (r3) - added link to edit.comp_brackets
20081226 - 1.0 (r4) - Added a new setting to the options page to record the path of the wp install from the root of the server. I did this so images can be found using file_exists
20090109 - 1.0 (r5) - Fixed the new 2.7 login box for FireFIx. Chrome / Safari need a box more tweaking
20090120 - 1.0 (r6) - Added the new function to update tv to the core so it is available to all the plugin pages.
20090124 - 1.1 - Bump to the 1.1 release.
20090126 - 1.1 (r1) - modification of add.match_player. began work on potential re-write
20090127 - 1.1 (r2) - finished work on add.match_player
20090130 - 1.1 (r3) - Began work on Did You Know management page. Incorperated page in Core below.
20090805 - 1.2 - Bump to 1.2 release
20090818 - 1.2 (r1) - began work towards 1.3 today I revised the remove player part of edit.player
20090819 - 1.2 (r2) - Added a link between add.match and add.match_player and fiddled with soem code on those pages.
20090831 - 1.2 (r3) - SOme slight changes to manage DYK. - added filter and increased the length of the DYK title. changed the summary generation around a bit
20090901 - 1.2 (r4) - Modified add player to incorporate new player types (mercs),
20090902 - 1.3 - added mercs to the JM report. bumped to 1.3 in preperation for go-live!
20091130 - 1.3.1 - added an update_player function to the core and updated edit.player (tracker [197])
					- Added a link to the new competition management page
20100123 - 1.3.1 (r1) - Updated the prefix for the custom bb tables in the Database (tracker [224])
*/

//stop people from accessing the file directly and causing errors.
if (!function_exists('add_action')) die('You cannot run this file directly. Naughty Person');

/************ Removal of Dashboard **********/
/*
//No longer required in WordPress 2.5
add_action('admin_head', 'bblm_remove_dashboard_js', 1);

function bblm_remove_dashboard_js() {
	remove_action('admin_head', 'index_js');
}*/
/************ Custom Login Box **********/

function bblm_custom_login() {
	echo '<link rel="stylesheet" type="text/css" href="' . get_settings('siteurl') . '/wp-content/plugins/bblm_plugin/bblm_plugin.css" />';
}

add_action('login_head', 'bblm_custom_login');

function change_wp_login_url() {
    echo  get_option('siteurl');
}

add_filter('login_headerurl', 'change_wp_login_url');


function change_wp_login_title() {
    echo  'Powered by ' . get_option('blogname');
}

add_filter('login_headertitle', 'change_wp_login_title');

/************ Removal of Default Widgets **********/
function bblm_remove_default_widgets() {
	if (function_exists('unregister_sidebar_widget')) {
		unregister_sidebar_widget('Meta');
	}
}

add_action('widgets_init','bblm_remove_default_widgets',0);


/************ Declaration / insertion of Admin Pages **********/
function bblm_insert_admin_pages() {
	//Addition of Top level admin pages

	add_menu_page('League Admin', 'BB: League Admin', 8, 'bblm_plugin/pages/bb.admin.core.welcome.php');
	add_menu_page('Match Management', 'BB: Match Admin', 8, 'bblm_plugin/pages/bb.admin.core.matchmanagement.php');
	add_menu_page('Team Management', 'BB: Team Admin', 8, 'bblm_plugin/pages/bb.admin.core.teamm.php');

	//Adds the subpages to the master heading - League Admin Pages
	add_submenu_page('bblm_plugin/pages/bb.admin.core.welcome.php', 'BB Settings', 'BB Settings', 8, 'bblm_plugin/pages/bb.admin.edit.options.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.welcome.php', 'Did You Know', 'Did You Know', 8, 'bblm_plugin/pages/bb.admin.manage.dyk.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.welcome.php', 'New Season', 'New Season', 8, 'bblm_plugin/pages/bb.admin.add.season.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.welcome.php', 'Add Cup', 'Add Cup', 8, 'bblm_plugin/pages/bb.admin.add.series.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.welcome.php', 'Add Competition', 'Add Competition', 8, 'bblm_plugin/pages/bb.admin.add.comp.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.welcome.php', 'Manage Comps', 'Manage Comps', 8, 'bblm_plugin/pages/bb.admin.manage.comps.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.welcome.php', 'Assign teams (comp)', 'Assign teams (comp)', 8, 'bblm_plugin/pages/bb.admin.edit.comp_team.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.welcome.php', 'Set-up Brackets (comp)', 'Set-up Brackets', 8, 'bblm_plugin/pages/bb.admin.add.comp_brackets.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.welcome.php', 'Edit Brackets (comp)', 'Edit Brackets', 8, 'bblm_plugin/pages/bb.admin.edit.comp_brackets.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.welcome.php', 'Add Stadium', 'Add Stadium', 8, 'bblm_plugin/pages/bb.admin.add.stadium.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.welcome.php', 'Create an Award', 'Create Award', 8, 'bblm_plugin/pages/bb.admin.add.award.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.welcome.php', 'Close a Competition', 'Close Comp', 8, 'bblm_plugin/pages/bb.admin.end.comp.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.welcome.php', 'Close a Season', 'Close Sea', 8, 'bblm_plugin/pages/bb.admin.end.season.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.welcome.php', 'Generate Weekly Summary', 'Gen Summary', 8, 'bblm_plugin/pages/bb.admin.generate.summary.php');

	//Adds the subpages to the master heading - Match Management Pages
	add_submenu_page('bblm_plugin/pages/bb.admin.core.matchmanagement.php', 'Record Match', 'Record Match', 8, 'bblm_plugin/pages/bb.admin.add.match.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.matchmanagement.php', 'Record Player Actions', 'Player Actions', 8, 'bblm_plugin/pages/bb.admin.add.match_player.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.matchmanagement.php', 'Edit Match details', 'Edit Match', 8, 'bblm_plugin/pages/bb.admin.edit.match.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.edit.match.php', 'Edit Match Trivia', 'Edit Match Trivia', 8, 'bblm_plugin/pages/bb.admin.edit.match_trivia.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.matchmanagement.php', 'Add Fixture', 'Add Fixture', 8, 'bblm_plugin/pages/bb.admin.add.fixture.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.matchmanagement.php', 'Edit Fixture', 'Edit Fixture', 8, 'bblm_plugin/pages/bb.admin.edit.fixture.php');

	//Adds the subpages to the master heading - Team Management Pages
	add_submenu_page('bblm_plugin/pages/bb.admin.core.teamm.php', 'Add Team', 'Add Team', 8, 'bblm_plugin/pages/bb.admin.add.team.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.teamm.php', 'Manage Teams', 'Manage Teams', 8, 'bblm_plugin/pages/bb.admin.edit.team.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.teamm.php', 'Add Race', 'Add Race', 8, 'bblm_plugin/pages/bb.admin.add.race.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.teamm.php', 'Add Position', 'Add Position', 8, 'bblm_plugin/pages/bb.admin.add.position.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.teamm.php', 'Manage Players', 'Manage Players', 8, 'bblm_plugin/pages/bb.admin.add.player.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.teamm.php', 'Edit Player', 'Edit Player', 8, 'bblm_plugin/pages/bb.admin.edit.player.php');
	add_submenu_page('bblm_plugin/pages/bb.admin.core.teamm.php', 'JM Report', 'JM Report', 8, 'bblm_plugin/pages/bb.admin.report.jm.php');

}
add_action('admin_menu', 'bblm_insert_admin_pages');


/************ Warzone Fitering **********/
$options = get_option('bblm_config');
$warzone_category = htmlspecialchars($options['cat_warzone'], ENT_QUOTES);


add_action('pre_get_posts', 'bs_remove_warzone_cat' );

function bs_remove_warzone_cat( $notused )
{
  global $wp_query;
  global $warzone_category;

  // Figure out if we need to exclude glossary - exclude from
  // archives (except category archives), feeds, and home page
  if( is_home() || is_feed() ||
      ( is_archive() && !is_category() )) {
     $wp_query->query_vars['cat'] = '-' . $warzone_category;
  }
}


add_filter('single_template', 'cpt_custom_post_template');

function cpt_custom_post_template($template) {
    global $wp_query;
    global $warzone_category;

    $post = $wp_query->post;
    $id = $warzone_category;

    // If a template exists for this post ID, load it.
//    if ( file_exists(TEMPLATEPATH . "/single-{$id}.php") )
//        return TEMPLATEPATH . "/single-{$id}.php";

    // Add custom checks here.  For example, give posts different templates
    // depending on what categories they are in.
	if ( in_category($id) && file_exists(TEMPLATEPATH . '/single-'.$id.'.php') )
      return TEMPLATEPATH . '/single-'.$id.'.php';

    return $template;
}

/************ Update TV function. Version 0.2 (20100123) **********/
function bblm_update_tv($tid) {
	global $wpdb;

	//Calculate worth of players
	$playervaluesql = 'SELECT SUM(P.p_cost_ng) FROM '.$wpdb->prefix.'player P WHERE P.p_status = 1 AND P.t_id = '.$tid;
	$tpvalue = $wpdb->get_var($playervaluesql);

	//Calcuate worth of rest of team (re-rolls, Assistant Coaches etc).
	$teamextravaluesql = 'SELECT SUM((R.r_rrcost*T.t_rr)+(T.t_ff*10000)+(T.t_cl*10000)+(T.t_ac*10000)+(T.t_apoc*50000)) AS TTOTAL FROM '.$wpdb->prefix.'team T, '.$wpdb->prefix.'race R WHERE R.r_id = T.r_id AND T.t_id = '.$tid;
	$tevalue = $wpdb->get_var($teamextravaluesql);

	//Add the two together
	$newtv = $tpvalue+$tevalue;

	//Generate SQL
	$sql = 'UPDATE `'.$wpdb->prefix.'team` SET `t_tv` = \''.$newtv.'\' WHERE `t_id` = '.$tid.' LIMIT 1';
	//Execute SQL
	//print("<h3>".$sql."</h3>");
	if (FALSE !== $wpdb->query($sql)) {
		$sucess = TRUE;
	}
	return true;
}
/************ Update Player function. Version 1.0b (20100123) **********/
function bblm_update_player($pid, $counts = 1) {
	//takes in two values, the player ID and a bool to see if only matches that count should be included
	global $wpdb;

	$playersppsql = 'SELECT SUM(M.mp_spp) FROM '.$wpdb->prefix.'match_player M WHERE M.p_id = '.$pid.' AND M.mp_spp > 0';
	if ($counts) {
		$playersppsql .= " AND M.mp_counts = 1";
	}
	$pspp = $wpdb->get_var($playersppsql);

	//Generate SQL
	$sql = 'UPDATE `'.$wpdb->prefix.'player` SET `p_spp` = \''.$pspp.'\' WHERE `p_id` = \''.$pid.'\' LIMIT 1';
	//Execute SQL
	if (FALSE !== $wpdb->query($sql)) {
		$sucess = TRUE;
	}
	return true;
}
?>