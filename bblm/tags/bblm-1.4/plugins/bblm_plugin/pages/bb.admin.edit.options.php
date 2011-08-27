<?php
/*
*	Filename: bb.admin.edit.options.php
*	Version: 1.2
*	Description: Page to manage options and settings of the bblm.
*/
/* -- Change History --
20080303 - 0.1b - Initial creation of file. Only Race page is recorded.
20080304 - 0.2b - Added Team page to be recorded.
20080305 - 0.3b - Added Series page to be recorded.
20080310 - 0.4b - Added Competition to be recorded.
20080312 - 0.5b - Added Match to be recorded.
20080402 - 0.6b - Added Stadium to be recorded.
20080405 - 0.7b - Added maxlength and length attributes to all the input fields
20080417 - 0.8b - Added stats limit option and split page into display and backend options
20080420 - 0.9b - Added stat to be recorded
20080428 - 0.10b - Added "TBD" team_id to be recorded
20080431 - 0.11b - Added Season parent and Warzone Category ID
20080730 - 1.0 - bump to Version 1 for public release.
20081226 - 1.1 - Added "Site Directory" so it can locate images of teams and other navigation
20090901 - 1.2 - Added Merc ID number for when creating players

*/
?>
<div class="wrap">
	<h2>League Options</h2>
	<p>Use the following Page to manage settings required by the system.</p>

<?php
	//Mapping of options to Vars.
	$options = get_option('bblm_config');
	if ( !is_array($options) ) {
		$options = array('title'=>'', 'topics'=>'');
	}

	//handle form sumit now
	if ( $_POST['bblm_options_submit'] ) {


		// Remember to sanitize and format use input appropriately.
		$options['page_race'] = strip_tags(stripslashes($_POST['bblm_options_page_race']));
		$options['page_team'] = strip_tags(stripslashes($_POST['bblm_options_page_team']));
		$options['page_series'] = strip_tags(stripslashes($_POST['bblm_options_page_series']));
		$options['page_comp'] = strip_tags(stripslashes($_POST['bblm_options_page_comp']));
		$options['page_match'] = strip_tags(stripslashes($_POST['bblm_options_page_match']));
		$options['page_stadium'] = strip_tags(stripslashes($_POST['bblm_options_page_stadium']));
		$options['page_stats'] = strip_tags(stripslashes($_POST['bblm_options_page_stats']));
		$options['page_season'] = strip_tags(stripslashes($_POST['bblm_options_page_season']));
		$options['display_stats'] = strip_tags(stripslashes($_POST['bblm_options_display_stats']));
		$options['team_tbd'] = strip_tags(stripslashes($_POST['bblm_options_team_tbd']));
		$options['cat_warzone'] = strip_tags(stripslashes($_POST['bblm_options_cat_warzone']));
		$options['site_dir'] = strip_tags(stripslashes($_POST['bblm_options_site_dir']));
		$options['player_merc'] = strip_tags(stripslashes($_POST['bblm_options_player_merc']));
		update_option('bblm_config', $options);
	?>
	<div id="updated" class="updated fade"><p>Settings have been saved.</p></div>
	<?php

	}

	// Be sure to format the options to be valid HTML attributes.
	$page_race = htmlspecialchars($options['page_race'], ENT_QUOTES);
	$page_team = htmlspecialchars($options['page_team'], ENT_QUOTES);
	$page_series = htmlspecialchars($options['page_series'], ENT_QUOTES);
	$page_comp = htmlspecialchars($options['page_comp'], ENT_QUOTES);
	$page_match = htmlspecialchars($options['page_match'], ENT_QUOTES);
	$page_stadium = htmlspecialchars($options['page_stadium'], ENT_QUOTES);
	$page_stats = htmlspecialchars($options['page_stats'], ENT_QUOTES);
	$page_season = htmlspecialchars($options['page_season'], ENT_QUOTES);
	$display_stats = htmlspecialchars($options['display_stats'], ENT_QUOTES);
	$cat_warzone = htmlspecialchars($options['cat_warzone'], ENT_QUOTES);
	$team_tbd = htmlspecialchars($options['team_tbd'], ENT_QUOTES);
	$site_dir = htmlspecialchars($options['site_dir'], ENT_QUOTES);
	$player_merc = htmlspecialchars($options['player_merc'], ENT_QUOTES);

?>
<form name="bblm_test" method="post" id="post">
<h3>Display Settings</h3>
<table class="form-table">
 <tr>
 	<th scope="row" valign="top">Statistics Limit</th>
 	<td>
 		<input id="bblm_options_display_stats" name="bblm_options_display_stats" type="text" value="<?php echo $display_stats ?>" maxlength="3" size="2" />
  	 	<label for="bblm_options_display_stats">The number of players and teams to display in the "top 10 stats".</label>
 	</td>
 </tr>
</table>

<h3>Technical and Other settings</h3>
<table class="form-table">
 <tr>
 	<th scope="row" valign="top">Site Directory</th>
 	<td>
 		<input id="bblm_options_site_dir" name="bblm_options_site_dir" type="text" value="<?php echo $site_dir ?>" maxlength="20" size="10" />
  	 	<label for="bblm_options_site_dir">The location of the wordpress install from the SERVER root WITHOUT Slashes. eg hdwsbbl</label>
 	</td>
 </tr>
 <tr>
 	<th scope="row" valign="top">Page # - Races</th>
 	<td>
 		<input id="bblm_options_page_race" name="bblm_options_page_race" type="text" value="<?php echo $page_race ?>" maxlength="3" size="2" />
  	 	<label for="bblm_options_page_race">The number in the DB for the Race Page.</label>
 	</td>
 </tr>
 <tr>
 	<th scope="row" valign="top">Page # - Teams</th>
 	<td>
 		<input id="bblm_options_page_team" name="bblm_options_page_team" type="text" value="<?php echo $page_team ?>" maxlength="3" size="2" />
  	 	<label for="bblm_options_page_team">The number in the DB for the Team Page.</label>
 	</td>
 </tr>
 <tr>
 	<th scope="row" valign="top">Page # - Championship Cups</th>
 	<td>
 		<input id="bblm_options_page_series" name="bblm_options_page_series" type="text" value="<?php echo $page_series ?>" maxlength="3" size="2" />
  	 	<label for="bblm_options_page_series">The number in the DB for the Series Page.</label>
 	</td>
 </tr>
 <tr>
 	<th scope="row" valign="top">Page # - Competitions</th>
 	<td>
 		<input id="bblm_options_page_comp" name="bblm_options_page_comp" type="text" value="<?php echo $page_comp ?>" maxlength="3" size="2" />
  	 	<label for="bblm_options_page_comp">The number in the DB for the Competitions Page.</label>
 	</td>
 </tr>

 <tr>
 	<th scope="row" valign="top">Page # - Match / Results</th>
 	<td>
 		<input id="bblm_options_page_match" name="bblm_options_page_match" type="text" value="<?php echo $page_match ?>" maxlength="3" size="2" />
  	 	<label for="bblm_options_page_match">The number in the DB for the Match / Results Page.</label>
 	</td>
 </tr>

 <tr>
  	<th scope="row" valign="top">Page # - Stadiums</th>
  	<td>
  		<input id="bblm_options_page_stadium" name="bblm_options_page_stadium" type="text" value="<?php echo $page_stadium ?>" maxlength="3" size="2" />
   	 	<label for="bblm_options_page_stadium">The number in the DB for the Stadiums Page.</label>
  	</td>
 </tr>
 <tr>
  	<th scope="row" valign="top">Page # - Statistics</th>
  	<td>
  		<input id="bblm_options_page_stats" name="bblm_options_page_stats" type="text" value="<?php echo $page_stats ?>" maxlength="3" size="2" />
   	 	<label for="bblm_options_page_stats">The number in the DB for the Statistics Page.</label>
  	</td>
 </tr>
  <tr>
   	<th scope="row" valign="top">Page # - Season</th>
   	<td>
   		<input id="bblm_options_page_season" name="bblm_options_page_season" type="text" value="<?php echo $page_season ?>" maxlength="3" size="2" />
    	 	<label for="bblm_options_page_season">The number in the DB for the Seasons Page.</label>
   	</td>
 </tr>
  <tr>
   	<th scope="row" valign="top">Category - Warzone</th>
   	<td>
   		<input id="bblm_options_cat_warzone" name="bblm_options_cat_warzone" type="text" value="<?php echo $cat_warzone ?>" maxlength="3" size="2" />
    	 	<label for="bblm_options_cat_warzone">The Cat ID for the Warzone (so it can be filtered).</label>
   	</td>
 </tr>
 <tr>
  	<th scope="row" valign="top">Team_id - To be Determined team</th>
  	<td>
  		<input id="bblm_options_team_tbd" name="bblm_options_team_tbd" type="text" value="<?php echo $team_tbd ?>" maxlength="3" size="2" />
   	 	<label for="bblm_options_team_tbd">The ID number for the team "To be determined".</label>
  	</td>
 </tr>
  <tr>
   	<th scope="row" valign="top">Player ID - Mercenary</th>
   	<td>
   		<input id="bblm_options_player_merc" name="bblm_options_player_merc" type="text" value="<?php echo $player_merc ?>" maxlength="3" size="2" />
    	<label for="bblm_options_player_merc">The ID number for the Merc position.</label>
   	</td>
 </tr>

</table>

<p class="submit">
	<input type="submit" name="bblm_options_submit" tabindex="4" value="Save Options" title="Save Options"/>
</p>

</form>

</div>