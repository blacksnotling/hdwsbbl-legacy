<?php
/*
Plugin Name: bblm_sidebar widgits
Plugin URI: http://www.hdwsbbl.co.uk/
Description: Provides a list of "other pages" and cutom "topic/cat listing"
Author: Blacksnotling
Version: 1.4
Author URI: http://www.blacksnotling.com/
*/

/*
*	Filename: bb.widgets.core.sidebar.php
*/
/* -- Change History --
20080227 - 0.1b - Intital creation of file.
20080301 - 0.2b - Changed name of file and tidied up widgets
20080609 - 0.3b - Added the List Competitions widget
20080616 - 0.3.1b - added a missing </div to list comps
20080730 - 1.0 - bump to Version 1 for public release.
20080805 - 1.1 - converted the restricted cat widgit to a dropdown
20080814 - 1.2 - fixed the date chacking below as the upcoming/active/recent status for comps was incorrect (formed 1.0.1)
20090330 - 1,3 - Editied to filter out non hdwsbbl details
20100123 - 1.4 - Updated the prefix for the custom bb tables in the Database (tracker [224])

*/

  //////////////////////////////
 // List Competitions Widgit //
//////////////////////////////
function widget_bblm_listcomps_init() {
	if ( !function_exists('register_sidebar_widget') )
		return;

	function widget_bblm_listcomps($args) {
		global $wpdb;
		extract($args);

		//Just print out the before_widget bit. note we are skipping the normal title part
		echo $before_widget;

		$url_parts = parse_url(get_bloginfo('home'));

		//meaty content goes here!
		//determine current Season
		$seasonsql = 'SELECT S.sea_id FROM '.$wpdb->prefix.'season S, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE S.sea_id = J.tid AND J.prefix = \'sea_\' AND J.pid = P.ID AND S.sea_active = 1 ORDER BY S.sea_sdate DESC LIMIT 1';
		$sea_id = $wpdb->get_var($seasonsql);

		$compsql = 'SELECT P.post_title, P.guid, C.c_active, UNIX_TIMESTAMP(C.c_sdate) AS sdate  FROM '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE C.c_id = J.tid AND J.prefix = \'c_\' AND J.pid = P.ID AND C.c_counts = 1 AND C.type_id = 1 AND C.sea_id = '.$sea_id.' ORDER BY C.c_active DESC, C.c_sdate ASC';
		if ($complisting = $wpdb->get_results($compsql)) {
			//set up the code below
			$is_first = 1;
			$last_stat = 0;
			$today = date("U");

			foreach ($complisting as $cl) {
				if (($cl->c_active) && ($cl->sdate < $today)) {

						if ((1 !== $last_stat) && (!$is_first)) {
							print("		</ul>\n	</div>\n");
							$is_first = 1;
						}
						if ($is_first) {
							print("	<div>\n	<h2>Active Competitions</h2>\n		<ul>\n");
							$is_first = 0;
						}
						print("			<li><a href=\"".$cl->guid."\" title=\"View more about ".$cl->post_title."\">".$cl->post_title."</a></li>\n");
						$last_stat = 1;
				}//end of active comp
				else if (($cl->c_active) && ($cl->sdate > $today)) {

						if ((2 !== $last_stat) && (!$is_first)) {
							print("		</ul>\n	</div>\n");
							$is_first = 1;
						}
						if ($is_first) {
							print("	<div>\n	<h2>Upcoming Competitions</h2>\n		<ul>\n");
							$is_first = 0;
						}
						print("			<li><a href=\"".$cl->guid."\" title=\"View more about ".$cl->post_title."\">".$cl->post_title."</a></li>\n");
						$last_stat = 2;
				}//end of upcoming comp
				else {

						if ((3 !== $last_stat) && (!$is_first)) {
							print("		</ul>\n	</div>\n");
							$is_first = 1;
						}
						if ($is_first) {
							print("	<div>\n	<h2>Recent Competitions</h2>\n		<ul>\n");
							$is_first = 0;
						}
						print("			<li><a href=\"".$cl->guid."\" title=\"View more about ".$cl->post_title."\">".$cl->post_title."</a></li>\n");
						$last_stat = 3;
				}//end of recent comp
			}//end of for each
			print("		</ul>\n	</div>\n");
		}//end of if sql


		//meaty content ends here!
		echo $after_widget;

	}
	register_sidebar_widget(array('bblm_List Comps', 'widgets'), 'widget_bblm_listcomps');
}

  /////////////////////////
 // Other Topics Widgit //
/////////////////////////

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_bblm_othertopics_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;
	// This is the function that outputs our little widgit.
	function widget_bblm_othertopics($args) {

		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);
		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_bblm_othertopics');
		$title = $options['title'];
		$topics = $options['topics'];

		// These lines generate our output. Widgets can be very complex
		// but as you can see here, they can also be very, very simple.

		echo $before_widget . $before_title . $title . $after_title;


		$url_parts = parse_url(get_bloginfo('home')); ?>

 		<ul>
		<?php
		wp_list_pages('sort_column=menu_order&title_li=&include='.$topics ); ?>
		</ul>


		<?php
		echo $after_widget;
	}

	// This is the function that outputs the form to let the users edit
	function widget_bblm_othertopics_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_bblm_othertopics');
		if ( !is_array($options) )
			$options = array('title'=>'', 'topics'=>'');
		if ( $_POST['bblm_ot-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['bblm_ot-title']));
			$options['topics'] = strip_tags(stripslashes($_POST['bblm_ot-topics']));
			update_option('widget_bblm_othertopics', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$topics = htmlspecialchars($options['topics'], ENT_QUOTES);

		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
				echo '<p style="text-align:right;"><label for="bblm_ot-title">' . __('Title:') . ' <input style="width: 200px;" id="bblm_ot-title" name="bblm_ot-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="bblm_ot-topics">' . __('Show Pages:') . ' <input style="width: 200px;" id="bblm_ot-topics" name="bblm_ot-topics" type="text" value="'.$topics.'"/></label></p>';
				echo '<input type="hidden" id="bblm_ot-submit" name="bblm_ot-submit" value="1" />';
	}

	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('bblm_Pages', 'widgets'), 'widget_bblm_othertopics');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control(array('bblm_Pages', 'widgets'), 'widget_bblm_othertopics_control', 300, 100);
}


  ///////////////////////////
 // Restricted Cat Widgit //
///////////////////////////

function widget_bblm_restricted_cat_init() {

	if ( !function_exists('register_sidebar_widget') )
		return;
	function widget_bblm_restricted_cat($args) {

		extract($args);
		$options = get_option('widget_bblm_restricted_cat');
		$title = $options['title'];
		$restricted = $options['rescats'];

		// These lines generate our output. Widgets can be very complex
		// but as you can see here, they can also be very, very simple.

		echo $before_widget . $before_title . $title . $after_title;
		?>


<?php

		//print("<ul>\n");
		//wp_list_categories('orderby=name&title_li=&exclude='.$restricted);
		//print("</ul>\n");

?>
		<form action="<?php bloginfo('url'); ?>/" method="get">
<?php
	$select = wp_dropdown_categories('orderby=name&title_li=&hide_empty=1&depth=1&echo=0&exclude='.$restricted);
	$select = preg_replace("#<select([^>]*)>#", "<select$1 onchange='return this.form.submit()'>", $select);
	echo $select;
?>
	<noscript><input type="submit" value="View" /></noscript>
	</form>


		<?php
		echo $after_widget;
	}

	function widget_bblm_restricted_cat_control() {

		$options = get_option('widget_bblm_restricted_cat');
		if ( !is_array($options) )
			$options = array('title'=>'', 'rescats'=>'');
		if ( $_POST['bblm_rc-submit'] ) {

			$options['title'] = strip_tags(stripslashes($_POST['bblm_rc-title']));
			$options['rescats'] = strip_tags(stripslashes($_POST['bblm_rc-restricted']));
			update_option('widget_bblm_restricted_cat', $options);
		}

		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$restricted = htmlspecialchars($options['rescats'], ENT_QUOTES);

				echo '<p style="text-align:right;"><label for="bblm_rc-title">' . __('Title:') . ' <input style="width: 200px;" id="bblm_rc-title" name="bblm_rc-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="bblm_rc-restricted">' . __('Hide Cats::') . ' <input style="width: 200px;" id="bblm_rc-restricted" name="bblm_rc-restricted" type="text" value="'.$restricted.'"/></label></p>';
				echo '<input type="hidden" id="bblm_rc-submit" name="bblm_rc-submit" value="1" />';
	}


	register_sidebar_widget(array('bblm_Categories', 'widgets'), 'widget_bblm_restricted_cat');
	register_widget_control(array('bblm_Categories', 'widgets'), 'widget_bblm_restricted_cat_control', 300, 100);
}


// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_bblm_othertopics_init');
add_action('widgets_init', 'widget_bblm_restricted_cat_init');
add_action('widgets_init', 'widget_bblm_listcomps_init');

//Finally, we remove the default widgets that these two will replace
function bblm_remove_replaced_widgets() {
	if (function_exists('unregister_sidebar_widget')) {
		unregister_sidebar_widget('Pages');
		unregister_sidebar_widget('Categories 1');
	}
}

add_action('widgets_init','bblm_remove_replaced_widgets',0);



?>