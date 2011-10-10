<?php
/*
*	Filename: bb.admin.core.teamm.php
*	Description: The front page to the Team Management section.
*/
?>
<div class="wrap">
<h2>Team Management</h2>
<p>From these pages you can administrate the Teams and Races that can be selected in your Blood Bowl League.</p>
<ul>
  <li><a href="<?php echo home_url();?>/wp-admin/admin.php?page=bblm_plugin/pages/bb.admin.add.team.php" title="Add a new Team">Add a new team to the league</a></li>
  <li><a href="<?php echo home_url();?>/wp-admin/admin.php?page=bblm_plugin/pages/bb.admin.edit.team.php" title="Manage Teams">Manage Teams</a></li>
  <li><a href="<?php echo home_url();?>/wp-admin/admin.php?page=bblm_plugin/pages/bb.admin.add.race.php" title="Add a new Race">Add a new Race to the league</a></li>
  <li>Edit an existing Race</li>
  <li><a href="<?php echo home_url();?>/wp-admin/admin.php?page=bblm_plugin/pages/bb.admin.add.position.php" title="Add a new Race">Assign a new position to a Race.</a></li>
  <li><a href="<?php echo home_url();?>/wp-admin/admin.php?page=bblm_plugin/pages/bb.admin.add.player.php" title="Add a new Player">Add a new player to a team.</a></li>
  <li><a href="<?php echo home_url();?>/wp-admin/admin.php?page=bblm_plugin/pages/bb.admin.edit.player.php" title="Manage Players">Manage Players on a team.</a></li>
  <li><a href="<?php echo home_url();?>/wp-admin/admin.php?page=bblm_plugin/pages/bb.admin.report.jm.php" title="Run the JM report">Run the Journeyman report.</a></li>
</ul>
</div>