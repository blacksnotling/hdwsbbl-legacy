<?php
/*
*	Filename: bb.admin.core.welcome.php
*	Description: A generic welcome page to the BBLM section of the admin section.
*/
?>
<div class="wrap">
<h2>Match Management Pages</h2>
<p>From these pages you can perform match related actions such as record a new match, recprd players actions and edit match details.</p>
<ul>
  <li><a href="<?php echo home_url();?>/wp-admin/admin.php?page=bblm_plugin/pages/bb.admin.add.match.php" title="Record match details">Record details of a match.</a></li>
  <li><a href="<?php echo home_url();?>/wp-admin/admin.php?page=bblm_plugin/pages/bb.admin.add.match_player.php" title="Record a players actions for a match">Record a players actions for a match.</a></li>
  <li><a href="<?php echo home_url();?>/wp-admin/admin.php?page=bblm_plugin/pages/bb.admin.edit.match.php" title="Edit match details">Edit match details (report, comments and facts).</a></li>
  <li><a href="<?php echo home_url();?>/wp-admin/admin.php?page=bblm_plugin/pages/bb.admin.add.fixture.php" title="Add a new fixture">Add a fixture.</a></li>
  <li><a href="<?php echo home_url();?>/wp-admin/admin.php?page=bblm_plugin/pages/bb.admin.edit.fixture.php" title="Edit a fixture">Edit a fixture.</a></li>
</ul>
</div>