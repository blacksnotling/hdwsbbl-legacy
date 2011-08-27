<?php
/*
*	Filename: bb.admin.add.fixture.php
*	Version: 1.2
*	Description: Page used to add a new award.
*/
/* -- Change History --
20080421 - 1.0b - Initial creation of file.
20080730 - 1.0 - bump to Version 1 for public release.
20090819 - 1.1 - Added the ability to have a fixture against the "To be determined Team"
				 + the fixture date defaults to next thursday by default
				 + General Tidy Up
20100124 - 1.2 - Updated the prefix for the custom bb tables in the Database (tracker [224])

*/

//Check the file is not being accessed directly
if (!function_exists('add_action')) die('You cannot run this file directly. Naughty Person');

?>
<div class="wrap">
	<h2>Add a Fixture</h2>
	<p>The Following page adds a fixture to the league.</p>

<?php



if (isset($_POST['bblm_add_fixture'])) {
/*	print("<pre>");
	print_r($_POST);
	print("</pre>");
	print("<hr />");*/


	$bblm_safe_input = array();

	$insertsql = 'INSERT INTO `'.$wpdb->prefix.'fixture` (`f_id`, `c_id`, `div_id`, `f_date`, `f_teamA`, `f_teamB`, `f_complete`) VALUES ';
	$p = 1;
	$is_first_fixture = 1;
	while ($p < ($_POST['bblm_fgames']+1)) {
		//we only want a comma added for all but the first
		if ($_POST['bblm_fadd'.$p]) {
			if (1 !== $is_first_fixture) {
				$insertsql .= ", ";
			}
			$insertsql .= '(\'\', \''.$_POST['bblm_fcomp'].'\', \''.$_POST['bblm_fdiv'].'\', \''.$_POST['fdate'.$p].' 00:00:01\', \''.$_POST['bblm_teamA'.$p].'\', \''.$_POST['bblm_teamB'.$p].'\', \'0\')';
		}

		$p++;
		$is_first_fixture = 0;
	}

	//print("<p>".$insertsql."</p>");

	if (FALSE !== $wpdb->query($insertsql)) {
		$sucess = TRUE;
	}
	else {
		$wpdb->print_error();
	}




?>
	<div id="updated" class="updated fade">
	<p>
	<?php
	if ($sucess) {
		print("Fixture(s) where Added.");
	}
	else {
		print("Something went wrong");
	}
	?>
</p>
	</div>
<?php

} //end of submit if
else if (isset($_POST['bblm_comp_select'])) {
/*	print("<pre>");
	print_r($_POST);
	print("</pre>");
	print("<hr />");*/

	$countmax = $_POST['bblm_fgames'];

?>
	<form name="bblm_addfixture" method="post" id="post">

	<p>Please enter the details of the fixtures below:</p>

	<input type="hidden" name="bblm_fgames" size="2" value="<?php print($countmax); ?>">
	<input type="hidden" name="bblm_fcomp" size="2" value="<?php print($_POST['bblm_fcomp']); ?>">
	<input type="hidden" name="bblm_fdiv" size="2" value="<?php print($_POST['bblm_fdiv']); ?>">

<?php
		//before we generate the list of fixtures, we need to grab the teams into an array
		$teamsql = "SELECT T.t_name, T.t_id FROM ".$wpdb->prefix."team T, ".$wpdb->prefix."team_comp C WHERE T.t_id = C.t_id AND C.c_id = ".$_POST['bblm_fcomp']." AND C.div_id = ".$_POST['bblm_fdiv'];
		$teams = $wpdb->get_results($teamsql, ARRAY_A);
		if (empty($teams)) {
					print("<p>No Teams have been entered into this stage of the Competition. You will need to add some first!</p>");
		}
		else {
			//generate output into a static string

			//Grab the ID of the 'To Be Determined Team'
			$options = get_option('bblm_config');
			$bblm_tbd_team = htmlspecialchars($options['team_tbd'], ENT_QUOTES);

			$teamlist = "";
			$teamlist .= "<option value=\"".$bblm_tbd_team."\">To be Determined</option>\n";
			foreach ($teams as $t) {
					$teamlist .= "<option value=\"".$t[t_id]."\">".$t[t_name]."</option>\n";
			}
			//Now we set our counter up
			$p = 1;
?>
<?php /*print($p);*/ ?>
	<table class="widefat">
		<thead>
		<tr>
			<th>Add</th>
			<th>Home</th>
			<th>Away</th>
			<th>Date</th>
		</tr>
		</thead>
		<tbody>
<?php
		while ($p < ($countmax+1)) {
?>
		<tr>
			<td><input type="checkbox" checked="checked" name="bblm_fadd<?php print($p); ?>"></td>
			<td><select name="bblm_teamA<?php print($p); ?>" id="bblm_teamA<?php print($p); ?>"><?php print($teamlist); ?></select></td>
			<td><select name="bblm_teamB<?php print($p); ?>" id="bblm_teamB<?php print($p); ?>"><?php print($teamlist); ?></select></td>
			<td><input name="fdate<?php print($p); ?>" type="text" size="12" maxlength="10" value="<?php print(date('Y-m-d', strtotime('next thursday'))); ?>"></td>
		</tr>
<?php
			$p++;
		} //emd of while
?>
		</tbody>
	</table>

	<p class="submit">
	<input type="submit" name="bblm_add_fixture" value="Create Fixtures" title="Create Fixtures"/>
	</p>
	</form>

<?php
	}//end of if $teams is empty
} //end of else if
else {
?>
	<form name="bblm_selectcomp" method="post" id="post">

	<p>Before we can begin, you must first select the competition and division that this match will take place in:</p>
	<h3>Select a Competition</h3>
	<fieldset id='addmatchdiv'>

	  <label for="bblm_fcomp" class="selectit">Competition</label>
	  <select name="bblm_fcomp" id="bblm_fcomp">
	<?php
	$compsql = 'SELECT c_id, c_name FROM '.$wpdb->prefix.'comp WHERE c_active = 1 order by c_name';
	//This line should work but for some reason prpduces blanks!
	//$compsql = 'SELECT C.c_id, C.c_name FROM '.$wpdb->prefix.'comp C, '.$wpdb->prefix.'bb2wp J, dev_posts P WHERE C.c_id = J.tid AND J.prefix = \'c_\' AND J.pid = P.ID AND C.c_active = 1 ORDER BY C.c_name ASC LIMIT';
	if ($comps = $wpdb->get_results($compsql)) {
		foreach ($comps as $comp) {
			print("<option value=\"$comp->c_id\">".$comp->c_name."</option>\n");
		}
	}
	?>
	</select>

	  <label for="bblm_fdiv" class="selectit">Division</label>
	  <select name="bblm_fdiv" id="bblm_fdiv">
	<?php
	$divsql = 'SELECT div_id, div_name FROM '.$wpdb->prefix.'division ORDER BY div_id';
	if ($divs = $wpdb->get_results($divsql)) {
		foreach ($divs as $div) {
			print("<option value=\"$div->div_id\">".$div->div_name."</option>\n");
		}
	}
	?>
	</select>

	<label for="bblm_fgames" class="selectit">Number of fixtures you wish to add:</label>
    <input type="text" name="bblm_fgames" size="3" value="1" id="bblm_fgames" maxlength="2">

	</fieldset>
	<p class="submit">
	<input type="submit" name="bblm_comp_select" value="Continue" title="Continue with selection"/>
	</p>
	</form>
<?php
} //end of else
?>

</div>