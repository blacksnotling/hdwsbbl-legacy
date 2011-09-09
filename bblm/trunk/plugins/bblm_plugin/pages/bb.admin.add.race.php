<?php
/*
*	Filename: bb.admin.add.race.php
*	Description: Page used to add a new race to the League.
*/
//Check the file is not being accessed directly
if (!function_exists('add_action')) die('You cannot run this file directly. Naughty Person');

?>
<div class="wrap">
	<h2>Add a Race</h2>
	<p>Use the following page to add a new Race to the League.</p>

<?php
if(isset($_POST['bblm_race_submit'])) {
	//Determine the parent page
	$options = get_option('bblm_config');
	$bblm_page_parent = htmlspecialchars($options['page_race'], ENT_QUOTES);

	$my_post = array(
		'post_title' => wp_filter_nohtml_kses($_POST['bblm_rname']),
		'post_content' => wp_filter_kses($_POST['bblm_rdesc']),
		'post_type' => 'page',
		'post_status' => 'publish',
		'comment_status' => 'closed',
		'ping_status' => 'closed',
		'post_parent' => $bblm_page_parent
	);
	if ($bblm_submission = wp_insert_post( $my_post )) {
		add_post_meta($bblm_submission, '_wp_page_template', 'bb.view.race.php');

		$bblmdatasql = 'INSERT INTO `'.$wpdb->prefix.'race` (`r_id`, `r_name`, `r_rrcost`, `r_active`, `r_show`) VALUES (\'\', \''.wp_filter_nohtml_kses($_POST['bblm_rname']).'\', \''.$_POST['bblm_rrrcost'].'\', \'1\', \'1\')';
		$wpdb->query($bblmdatasql);

		$bblmmappingsql = 'INSERT INTO `'.$wpdb->prefix.'bb2wp` (`bb2wp_id`, `tid`, `pid`, `prefix`) VALUES (\'\',\''.$wpdb->insert_id.'\', \''.$bblm_submission.'\', \'r_\')';
		$wpdb->query($bblmmappingsql);

		$success = 1;
		$addattempt = 1;
	} //end of if post insertion was successful
?>
	<div id="updated" class="updated fade">
		<p>
	<?php
	if ($success) {
		print("Race has been created. <a href=\"".get_permalink($bblm_submission)."\" title=\"View the new Race\">View page</a>");
	}
	else {
		print("Something went wrong! Please try again.");
	}


?>
		</p>
	</div>
<?php
}//end of submit if
?>
	<form name="bblm_test" method="post" id="post">

		<table class="form-table">
			<tr valign="top">
					<th scope="row"><label for="bblm_rname">Race Name</label></th>
		  			<td><input type="text" name="bblm_rname" size="20" value="" id="bblm_rname" class="regular-text"/></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="bblm_rrrcost">Re-Roll Cost</label></th>
		  		<td><input type="text" name="bblm_rrrcost" size="6" value="10000" class="regular-text"/>gp</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="bblm_rdesc">Description</label></th>
				<td><p><textarea rows="10" cols="50" name="bblm_rdesc" id="bblm_rdesc" class="large-text"></textarea></p></td>
			</tr>
		</table>
		<p class="submit"><input type="submit" name="bblm_race_submit" tabindex="4" value="Add Race" title="Add the Race" class="button-primary"/></p>

	</form>


	<h3>Existing Races</h3>
	<p>The following races already exist in the League:</p>
	<?php
	$namesql = 'SELECT * FROM `'.$wpdb->prefix.'race` WHERE r_show = 1 order by r_name';

	if ($names = $wpdb->get_results($namesql)) {
		print("	<ul>\n");
		foreach ($names as $name) {
			print("		<li>".$name->r_name."</li>\n");
		}
		print("	</ul>\n");
	}
	else {
		print("	<p><strong>No Races have been added yet.</strong></p>\n");
	}
?>
</div>