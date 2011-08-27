<?php
/*
*	Filename: bb.admin.add.team.php
*	Version: 1.3
*	Description: This page is used to add a new team to the BBLM.
*/
/* -- Change History --
20080304 - 0.0b - Initial creation of file.
20080304 - 1.0b - Page is working and calling on correct wp vars for tables. (Still need to fix custon '.$wpdb->prefix.')
20080305 - 1.1b - modified the $joinsql to take into account the DB change
20080402 - 1.2b - modified sql string for stadium from s_id to stad_id.
20080403 - 1.3b - added maxlength to inputs, edited sql for change in db and added ability to select home stadium
20080408 - 1.4b - Modified pafe to include Roster generation
20080409 - 1.5b - Split the select a race bit out to the front. this allows me to populate more fields in the main page.
20080730 - 1.0 - bump to Version 1 for public release.
20080804 - 1.0.1 - started to re-style the page to match WP
20080804 - 1.1 - Completed re-design and implemented Javascript to calculate the TV and remaining Bank
20090606 - 1.2 - Updaed the insert string due to addition of t_gui to the '.$wpdb->prefix.'team schema
20100124 - 1.3 - Updated the prefix for the custom bb tables in the Database (tracker [224])

*/

//Check the file is not being accessed directly
if (!function_exists('add_action')) die('You cannot run this file directly. Naughty Person');

/* Original SQL code for '.$wpdb->prefix.'team
$sql = 'INSERT INTO `'.$wpdb->prefix.'team` (`t_id`, `t_name`, `r_id`, `ID`, `t_hcoach`, `t_ff`, `t_rr`, `t_apoc`, `t_cl`, `t_ac`, `t_bank`, `t_tv`, `t_active`, `t_show`, `type_id`, `t_sname`, `stad_id`) VALUES (\'\', \'Test Team\', \'1\', \'1\', \'Mr Test Coach\', \'3\', \'2\', \'1\', \'0\', \'0\', \'10000\', \'1000000\', \'1\', \'1\', \'1\', \'tst\', \'1\')';*/




?>
<div class="wrap">
	<h2>Create a new Team</h2>

<?php
if(isset($_POST['bblm_team_submit'])) {
/*	print("<pre>");
	print_r($_POST);
	print("</pre>");
	print("<hr />");*/


	$bblm_safe_input = array();

	//think about a for each
	if (get_magic_quotes_gpc()) {
		$_POST['bblm_tname'] = stripslashes($_POST['bblm_tname']);
		$_POST['bblm_tdesc'] = stripslashes($_POST['bblm_tdesc']);
		$_POST['bblm_sname'] = stripslashes($_POST['bblm_sname']);

	}

	$bblm_safe_input['tname'] = $wpdb->escape($_POST['bblm_tname']);
	$bblm_safe_input['tdesc'] = $wpdb->escape($_POST['bblm_tdesc']);
	$bblm_safe_input['sname'] = $wpdb->escape($_POST['bblm_sname']);

	$bblm_safe_input['trace'] = $_POST['bblm_trace'];
	$bblm_safe_input['tuser'] = $_POST['bblm_tuser'];
	$bblm_safe_input['thcoach'] = $_POST['bblm_thcoach'];
	$bblm_safe_input['tstad'] = $_POST['bblm_tstad'];
	$bblm_safe_input['trr'] = $_POST['bblm_trr'];
	$bblm_safe_input['tff'] = $_POST['bblm_tff'];
	$bblm_safe_input['tapoc'] = $_POST['bblm_tapoc'];
	$bblm_safe_input['tcl'] = $_POST['bblm_tcl'];
	$bblm_safe_input['tac'] = $_POST['bblm_tac'];
	$bblm_safe_input['tbank'] = $_POST['bblm_tbank'];
	$bblm_safe_input['ttype'] = $_POST['bblm_ttype'];
	$bblm_safe_input['ttv'] = $_POST['bblm_ttv'];
	//Set the default "on" result from a checkbox to a 1
	if (on == $_POST['bblm_roster']) {
		$_POST['bblm_roster'] = 1;
	}
	else {
		$_POST['bblm_roster'] = 0;
	}
	$bblm_safe_input['roster'] = $_POST['bblm_roster'];


	//generate time NOW.
	$bblm_date_now = date('Y-m-j H:i:59');

	//pull parent page from somewhere
	$options = get_option('bblm_config');
	$bblm_page_parent = htmlspecialchars($options['page_team'], ENT_QUOTES);

	//filter page body
	$bblm_removable = array("<pre>","</pre>","<p>","&nbsp;");
	$bblm_page_content = $bblm_safe_input['tdesc'];
	$bblm_page_content = str_replace($bblm_removable,"",$bblm_page_content);
	$bblm_page_content = str_replace("</p>","\n\n",$bblm_page_content);

	//snaitise page title
	$bblm_page_title = $bblm_safe_input['tname'];

	//convert page title to slug
	$bblm_page_slug = sanitize_title($bblm_page_title);

	//generate GUID
	$bblm_guid = get_bloginfo('wpurl');
	$bblm_guid .= "/teams/";
	$bblm_guid .= $bblm_page_slug;


$postsql = 'INSERT INTO '.$wpdb->posts.' (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_category`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES (\'\', \'1\', \''.$bblm_date_now.'\', \''.$bblm_date_now.'\', \''.$bblm_page_content.'\', \''.$bblm_page_title.'\', \'0\', \'\', \'publish\', \'closed\', \'closed\', \'\', \''.$bblm_page_slug.'\', \'\', \'\', \''.$bblm_date_now.'\', \''.$bblm_date_now.'\', \'\', \''.$bblm_page_parent.'\', \''.$bblm_guid.'\', \'0\', \'page\', \'\', \'0\')';


if (FALSE !== $wpdb->query($postsql)) {
	$bblm_post_number = $wpdb->insert_id;//captured from SQL string
}
else {
	$wpdb->print_error();
}

$postmetasql = 'INSERT INTO '.$wpdb->postmeta.' (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES (\'\', \''.$bblm_post_number.'\', \'_wp_page_template\', \'bb.view.team.php\')';

if (FALSE !== $wpdb->query($postmetasql)) {
	$sucess = TRUE;
}
else {
	$wpdb->print_error();
}

$teamsql = 'INSERT INTO `'.$wpdb->prefix.'team` (`t_id`, `t_name`, `r_id`, `ID`, `t_hcoach`, `t_ff`, `t_rr`, `t_apoc`, `t_cl`, `t_ac`, `t_bank`, `t_tv`, `t_active`, `t_show`, `type_id`, `t_sname`, `stad_id`, `t_img`, `t_roster`, `t_guid`) VALUES (\'\', \''.$bblm_page_title.'\', \''.$bblm_safe_input['trace'].'\', \''.$bblm_safe_input['tuser'].'\', \''.$bblm_safe_input['thcoach'].'\', \''.$bblm_safe_input['tff'].'\', \''.$bblm_safe_input['trr'].'\', \''.$bblm_safe_input['tapoc'].'\', \''.$bblm_safe_input['tcl'].'\', \''.$bblm_safe_input['tac'].'\', \''.$bblm_safe_input['tbank'].'\', \''.$bblm_safe_input['ttv'].'\', \'1\', \'1\', \''.$bblm_safe_input['ttype'].'\', \''.$bblm_safe_input['sname'].'\', \''.$bblm_safe_input['tstad'].'\', \'\', \''.$bblm_safe_input['roster'].'\', \''.$bblm_guid.'\')';

if (FALSE !== $wpdb->query($teamsql)) {
	$bblm_team_number = $wpdb->insert_id;//captured from SQL string
}
else {
	$wpdb->print_error();
}

$joinsql = 'INSERT INTO `'.$wpdb->prefix.'bb2wp` (`bb2wp_id`, `tid`, `pid`, `prefix`) VALUES (\'\', \''.$bblm_team_number.'\', \''.$bblm_post_number.'\', \'t_\')';

if (FALSE !== $wpdb->query($joinsql)) {
	$sucess = TRUE;
}
else {
	$wpdb->print_error();
}


//Now we begin the insertions for roster:
if ($bblm_safe_input['roster']) {

	$roster_slug = $bblm_guid."/roster";

	$rostersql = 'INSERT INTO '.$wpdb->posts.' (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_category`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES (\'\', \'1\', \''.$bblm_date_now.'\', \''.$bblm_date_now.'\', \'\', \'Roster - '.$bblm_page_title.'\', \'0\', \'\', \'publish\', \'closed\', \'closed\', \'\', \'roster\', \'\', \'\', \''.$bblm_date_now.'\', \''.$bblm_date_now.'\', \'\', \''.$bblm_post_number.'\', \''.$roster_slug.'\', \'0\', \'page\', \'\', \'0\')';

	if (FALSE !== $wpdb->query($rostersql)) {
		$bblm_roster_number = $wpdb->insert_id;//captured from SQL string
	}
	else {
		$wpdb->print_error();
	}

	$rosterpostmetasql = 'INSERT INTO '.$wpdb->postmeta.' (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES (\'\', \''.$bblm_roster_number.'\', \'_wp_page_template\', \'bb.view.roster.php\')';

	if (FALSE !== $wpdb->query($rosterpostmetasql)) {
		$sucess = TRUE;
	}
	else {
		$wpdb->print_error();
	}

	$rosterjoinsql = 'INSERT INTO `'.$wpdb->prefix.'bb2wp` (`bb2wp_id`, `tid`, `pid`, `prefix`) VALUES (\'\', \''.$bblm_team_number.'\', \''.$bblm_roster_number.'\', \'roster\')';

	if (FALSE !== $wpdb->query($rosterjoinsql)) {
		$sucess = TRUE;
	}
	else {
		$wpdb->print_error();
	}
}

// Now we flush the re-write rules to make them regenerate the rules to include our new page.
	$wp_rewrite->flush_rules();


?>
	<div id="updated" class="updated fade">
	<p>
	<?php
	if ($sucess) {
		print("Team has been added. <a href=\"".$bblm_guid."\" title=\"View the new team\">View page</a>");
		if ($bblm_safe_input['roster']) {
			print(" | <a href=\"".$bblm_guid."/roster\" title=\"View the new teams Roster\">View Roster</a>");
		}
	}
	else {
		print("Something went wrong");
	}
	?>
.</p>
	</div>
<?php
//end of submit if
}
else if(isset($_POST['bblm_race_select'])) {
	  ///////////////////////////////
	 // Begin Output of main form //
	///////////////////////////////
?>

	<form name="bblm_addteam" method="post" id="post">

<div id="poststuff">

<!-- the sidebar bit goes first -->
<div class="submitbox" id="submitpost">
<div id="previewview">
</div>
<div class="inside">
<p><strong>Finished?</strong></p>
<p>If you are happy with the team details then press the button below to create the team!</p>
</div> <!-- end of ".inside" -->


<p class="submit">
	<input type="submit" name="bblm_team_submit" value="Create Team" title="Add the Team" />
<br class="clear" />
</p>

<div class="side-info">
	<!-- Any Side bar information goes here -->
</div> <!-- end of ".side-info" -->


</div> <!-- end of ".submitbox" -->

<div id="post-body">
<div id="titlediv">
<h3><label for="bblm_tname">Team Name</label></h3>

<div id="titlewrap">
	<input type="text" name="bblm_tname" size="50" tabindex="1" value="" id="bblm_tname" maxlength="50" style="width:95%;outline:none;border:0;font-size: 1.7em;">
</div>
</div>


<div id="postdivrich" class="postarea">
<h3><label for="content">Team Bio</label></h3>

	<script type="text/javascript" src="../wp-includes/js/tinymce/tiny_mce.js"></script>
	<script type="text/javascript">
	<!--
		tinyMCE.init({
		theme : "advanced",
		mode : "exact",
		elements : "bblm_tdesc",
		width : "700",
		height : "300"
		});
	-->
	</script>


    <!--<div id='editorcontainer'><textarea class='' rows='15' cols='40' name='content' tabindex='2' id='content'></textarea></div>-->
    <div id='editorcontainer'><textarea class='mceEditor' rows='15' cols='40' name='bblm_tdesc' tabindex='2' id='bblm_tdesc'></textarea></div>


</div><!-- end of postarea" -->


	<h3>Other Information</h3>

	<table class="form-table">
	<tr class="form-field form-required">
		<th scope="row" valign="top"><label for="bblm_sname" class="selectit">Short Name</label></th>
		<td><input type="text" name="bblm_sname" size="3" tabindex="2" value="" id="bblm_sname" maxlength="5"><br />
		This will be displayed on various reports and pages.</td>
	</tr>
	<tr class="form-field form-required">
		<th scope="row" valign="top"><label for="bblm_tuser" class="selectit">Coach</label></th>
		<td><select name="bblm_tuser" id="bblm_tuser">
<?php
		$usersql = 'SELECT ID, display_name FROM '.$wpdb->users.' order by display_name';
		if ($users = $wpdb->get_results($usersql)) {
			foreach ($users as $user) {
				print("<option value=\"".$user->ID."\">".$user->display_name."</option>\n");
			}
		}
?>
		</select></td>
	</tr>
	<tr class="form-field form-required">
		<th scope="row" valign="top"><label for="bblm_thcoach" class="selectit">Head Coach</label></th>
		<td><input type="text" name="bblm_thcoach" size="25" tabindex="3" value="Unkown" maxlength="25"></td>
	</tr>
	<tr class="form-field form-required">
		<th scope="row" valign="top"><label for="bblm_tstad" class="selectit">Home Stadium</label></th>
		<td><select name="bblm_tstad" id="bblm_tstad">
<?php
		$stadsql = 'SELECT S.* FROM '.$wpdb->prefix.'stadium S, '.$wpdb->prefix.'bb2wp J, '.$wpdb->posts.' P WHERE S.stad_id = J.tid AND J.prefix = \'stad_\' AND J.pid = P.ID ORDER BY S.stad_name';
		if ($stadiums = $wpdb->get_results($stadsql)) {
			foreach ($stadiums as $stad) {
				print("<option value=\"".$stad->stad_id."\">".$stad->stad_name."</option>\n");
			}
		}
?>
		</select></td>
	</tr>
	<tr class="form-field form-required">
		<th scope="row" valign="top"><label for="bblm_roster" class="selectit">Generate Roster?</label></th>
	      <td><input type="checkbox" name="bblm_roster" checked="checked"></td>
	</tr>
	<tr class="form-field form-required">
		<th scope="row" valign="top"><label for="bblm_ttype" class="selectit">Team Type</label></th>
		  <td><select name="bblm_ttype" id="bblm_tuser">
<?php
		$typesql = 'SELECT type_id, type_name FROM '.$wpdb->prefix.'team_type ORDER BY type_id';
		if ($types = $wpdb->get_results($typesql)) {
			foreach ($types as $type) {
				print("<option value=\"$type->type_id\">".$type->type_name."</option>\n");
			}
		}
?>
		</select></td>
	</tr>

	</table

<hr />


	<h3>Initial Purchases</h3>

<script type="text/javascript">
function UpdateBankTv() {
	var tot_rr = document.getElementById('bblm_trr').value * document.getElementById('bblm_trrcost').value;

	var tot_ff = document.getElementById('bblm_tff').value * 10000;

	var tot_cl = document.getElementById('bblm_tcl').value * 10000;

	var tot_ac = document.getElementById('bblm_tac').value * 10000;

	var tot_apoc = document.getElementById('bblm_tapoc').value * 50000;

	var tot_tv = tot_rr + tot_ff + tot_cl + tot_ac + tot_apoc;
	document.getElementById('bblm_ttv').value = tot_tv;

	var tot_bank = 1000000 - tot_tv;
	document.getElementById('bblm_tbank').value = tot_bank;
}
</script>

<?php
		$racesql = "SELECT r_id, r_name, r_rrcost FROM ".$wpdb->prefix."race WHERE r_id = ".$_POST['bblm_rid'];
		if ($races = $wpdb->get_results($racesql)) {
			foreach ($races as $race) {
				$rid = $race->r_id;
				$rrcost = $race->r_rrcost;
			}
		}
?>

	<table class="form-table">
	<tr class="form-field form-required">
		<th scope="row" valign="top"><label for="bblm_trr" class="selectit">Re-Rolls</label></th>
		<td><input type="text" name="bblm_trr" size="2" tabindex="4" value="0" maxlength="1" id="bblm_trr"><br />
		@ <?php print($rrcost); ?> each</td>
	</tr>

	<tr class="form-field form-required">
		<th scope="row" valign="top"><label for="bblm_tff" class="selectit">Fan Factor</label></th>
		  <td><input type="text" name="bblm_tff" size="2" tabindex="5" value="0" maxlength="2" id="bblm_tff"><br />
		  @ 10,000 each</td>
	</tr>
	<tr class="form-field form-required">
		<th scope="row" valign="top"><label for="bblm_tcl" class="selectit">Cheerleaders</label></th>
		  <td><input type="text" name="bblm_tcl" size="2" tabindex="7" value="0" maxlength="2" id="bblm_tcl"><br />
		  @ 10,000 each</td>
	</tr>
	<tr class="form-field form-required">
		<th scope="row" valign="top"><label for="bblm_tac" class="selectit">Assistant Coaches</label></th>
		  <td><input type="text" name="bblm_tac" size="2" tabindex="8" value="0" maxlength="2" id="bblm_tac"><br />
		  @ 10,000 each</td>
	</tr>
	<tr class="form-field form-required">
		<th scope="row" valign="top"><label for="bblm_tapoc" class="selectit">Apothecary</label></th>
		  <td><input type="text" name="bblm_tapoc" size="1" tabindex="6" value="0" maxlength="1" id="bblm_tapoc"><br />
		  @ 10,000 each</td>
	</tr>
	<tr class="form-field form-required">
		<th scope="row" valign="top">&nbsp;</th>
		  <td><input type="button" value="Update Bank + TV" onClick="UpdateBankTv();"></td>
	</tr>

	<tr class="form-field form-required">
		<th scope="row" valign="top"><label for="bblm_tbank" class="selectit">Remaining Bank</label></th>
		  <td><input type="text" name="bblm_tbank" size="7" tabindex="9" value="1000000" maxlength="7" id="bblm_tbank">gp</td>
	</tr>
	<tr class="form-field form-required">
		<th scope="row" valign="top"><label for="bblm_ttv" class="selectit">Team Value (initial)</label></th>
		  <td><input type="text" name="bblm_ttv" size="7" tabindex="10" value="0" maxlength="7" id="bblm_ttv">gp</td>
	</tr>
	</table>

			<input type="hidden" name="bblm_trace" size="3" value="<?php print($rid); ?>">
			<input type="hidden" name="bblm_trrcost" id="bblm_trrcost" maxlength="6" value="<?php print($rrcost); ?>">


</div><!-- end of "post-body"-->
</div> <!-- end of "poststuff" -->
</form>

<?php
}//end of elseIF
else {
?>
	<form name="bblm_addposition" method="post" id="post">

	<p>The following page cn be used to create a new team and add them to the HDWSBBL.</p>
	<p>Before you can begin creating the new team, you must first select the Race of the new team:</p>


	<table class="form-table">
	<tr class="form-field form-required">
		<th scope="row" valign="top"><label for="bblm_rid">Race</label></th>
		<td><select name="bblm_rid" id="bblm_rid">
<?php
		$racesql = "SELECT R.r_id, R.r_name FROM ".$wpdb->prefix."race R, ".$wpdb->prefix."bb2wp J, ".$wpdb->posts." P WHERE R.r_id = J.tid AND J.pid = P.ID AND J.prefix = 'r_' ORDER BY R.r_name ASC";
			if ($races = $wpdb->get_results($racesql)) {
				foreach ($races as $race) {
					print("			<option value=\"$race->r_id\">".$race->r_name."</option>\n");
				}
			}
?>
		</select></td>
	</tr>
	</table>

	<p class="submit">
	<input type="submit" name="bblm_race_select" tabindex="4" value="Select above Race" title="Select the above Race"/>
	</p>
	</form>
<?php
} //end of else section
?>
</div>