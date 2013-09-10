<?php
/*
*	Filename: bb.admin.add.race.php
*	Version: 1.1
*	Description: Page used to add a new race to the BBLM.
*/
/* -- Change History --
20080303 - 0.0b - Initial creation of file Based on the file from previous testing
20080304 - 1.0b - Page is working and calling on correct wp vars for tables. (Still need to fix custon '.$wpdb->prefix.')
20080305 - 1.1b - Change the confirm message when a race is added successfully to something more helpful.
20080305 - 1.2b - modified the $joinsql to take into account the DB change
20080730 - 1.0 - bump to Version 1 for public release.
20100124 - 1.1 - Updated the prefix for the custom bb tables in the Database (tracker [224])

*/

//Check the file is not being accessed directly
if (!function_exists('add_action')) die('You cannot run this file directly. Naughty Person');

?>
<div class="wrap">
	<h2>Add a Race</h2>
	<p>Use the following form to add a new Race to the HDWSBBL.</p>

<?php
if(isset($_POST['bblm_race_submit'])) {
	$bblm_safe_input = array();

	//think about a for each
	if (get_magic_quotes_gpc()) {
	$_POST['bblm_rname'] = stripslashes($_POST['bblm_rname']);
	$_POST['bblm_rdesc'] = stripslashes($_POST['bblm_rdesc']);

	}
	$bblm_safe_input['rname'] = stripslashes($_POST['bblm_rname']);
	$bblm_safe_input['rdesc'] = stripslashes($_POST['bblm_rdesc']);
	$bblm_safe_input['rrcost'] = stripslashes($_POST['bblm_rrrcost']);

	$bblm_safe_input['rname'] = $wpdb->escape($bblm_safe_input['rname']);
	$bblm_safe_input['rdesc'] = $wpdb->escape($bblm_safe_input['rdesc']);
	$bblm_safe_input['rrcost'] = $wpdb->escape($bblm_safe_input['rrcost']);

	//generate time NOW.
	$bblm_date_now = date('Y-m-j H:i:59');

	//pull parent page from somewhere
	$options = get_option('bblm_config');
	$bblm_page_parent = htmlspecialchars($options['page_race'], ENT_QUOTES);

	//filter page body
	$bblm_removable = array("<pre>","</pre>","<p>","&nbsp;");
	$bblm_page_content = $bblm_safe_input['rdesc'];
	$bblm_page_content = str_replace($bblm_removable,"",$bblm_page_content);
	$bblm_page_content = str_replace("</p>","\n\n",$bblm_page_content);

	//snaitise page title
	$bblm_page_title = $bblm_safe_input['rname'];

	//convert page title to slug
	$bblm_page_slug = sanitize_title($bblm_page_title);

	//generate GUID
	$bblm_guid = get_bloginfo('wpurl');
	$bblm_guid .= "/races/";
	$bblm_guid .= $bblm_page_slug;

	$bblm_rr_cost = $bblm_safe_input['rrcost'];


$postsql = 'INSERT INTO '.$wpdb->posts.' (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_category`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES (\'\', \'1\', \''.$bblm_date_now.'\', \''.$bblm_date_now.'\', \''.$bblm_page_content.'\', \''.$bblm_page_title.'\', \'0\', \'\', \'publish\', \'closed\', \'closed\', \'\', \''.$bblm_page_slug.'\', \'\', \'\', \''.$bblm_date_now.'\', \''.$bblm_date_now.'\', \'\', \''.$bblm_page_parent.'\', \''.$bblm_guid.'\', \'0\', \'page\', \'\', \'0\')';



if (FALSE !== $wpdb->query($postsql)) {
	$bblm_post_number = $wpdb->insert_id;//captured from SQL string
}
else {
	$wpdb->print_error();
}

$postmetasql = 'INSERT INTO '.$wpdb->postmeta.' (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES (\'\', \''.$bblm_post_number.'\', \'_wp_page_template\', \'bb.view.race.php\')';

if (FALSE !== $wpdb->query($postmetasql)) {
	$sucess = TRUE;
}
else {
	$wpdb->print_error();
}

$racesql = 'INSERT INTO `'.$wpdb->prefix.'race` (`r_id`, `r_name`, `r_rrcost`) VALUES (\'\', \''.$bblm_page_title.'\', \''.$bblm_rr_cost.'\')';

if (FALSE !== $wpdb->query($racesql)) {
	$bblm_race_number = $wpdb->insert_id;//captured from SQL string
}
else {
	$wpdb->print_error();
}

$joinsql = 'INSERT INTO `'.$wpdb->prefix.'bb2wp` (`bb2wp_id`, `tid`, `pid`, `prefix`) VALUES (\'\', \''.$bblm_race_number.'\', \''.$bblm_post_number.'\', \'r_\')';

if (FALSE !== $wpdb->query($joinsql)) {
	$sucess = TRUE;
}
else {
	$wpdb->print_error();
}

// Now we flush the re-write rules to make them regenerate the rules to include our new page.
	$wp_rewrite->flush_rules();

?>
	<div id="updated" class="updated fade">
	<p>
	<?php
	if ($sucess) {
		print("Race has been added. <a href=\"".$bblm_guid."\" title=\"View the new race\">View page</a>");
	}
	else {
		print("Something went wrong");
	}
?>
</p>
	</div>
<?php
}
?>

	<form name="bblm_test" method="post" id="post">

	<fieldset id='addracediv'><legend>Race Details</legend>
		<div>
		  <label for="bblm_rname" class="selectit">Race Name</label>
		  <input type="text" name="bblm_rname" size="20" tabindex="1" value="" id="bblm_rname">
		  <label for="bblm_rrrcost" class="selectit">Re-Roll Cost</label>
		  <input type="text" name="bblm_rrrcost" size="6" tabindex="3" value="">
		</div>
	</fieldset>

	<fieldset id="postdivrich"><legend>Page Content</legend>
	<script type="text/javascript" src="../wp-includes/js/tinymce/tiny_mce.js"></script>
	<script type="text/javascript">
	<!--
		tinyMCE.init({
		theme : "advanced",
		mode : "exact",
		elements : "bblm_rdesc",
		width : "565",
		height : "200"
		});
	-->
	</script>

	<div>
	  <textarea class='mceEditor' rows='10' cols='40' name='bblm_rdesc' tabindex='2' id='bblm_rdesc'></textarea>
	</div>
	</fieldset>

	<p class="submit">
	<input type="submit" name="bblm_race_submit" tabindex="4" value="Add Race" title="Add the Race"/>
	</p

	</form>


	<h3>Existing Races</h3>
	<p>The following races already exist in the HDWSBBL:</p>
	<?php
	$namesql = 'SELECT * FROM `'.$wpdb->prefix.'race` order by r_name';

	if ($names = $wpdb->get_results($namesql)) {
		print("<ul>");
		foreach ($names as $name) {
			print("<li>".$name->r_name."</li>");
		}
		print("</ul>");
	}
	else {
		print("<p><strong>No Races could be found!</strong></p>");
	}
?>
</div>