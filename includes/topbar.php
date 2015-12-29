<div class="navbar navbar-default navbar-fixed-top">
	<div class="navbar-header">

		<a id="site-title" class="navbar-brand" href="http://www.partytreasury.com/" title="Party Treasury" rel="home"  class="navbar-brand"><img alt="Party Treasury" src="/images/pt_white.png" style="width: 40px;"><?php if ( is_user_logged_in() AND $GLOBALS['vault'] != 0 ) { echo "&nbsp;".$GLOBALS['vName']; } ?></a>
	</div>
<div>
      <ul class="nav navbar-nav pull-right">
         <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</a>

<?php if ( is_user_logged_in() ) { 
	$redirect = ( is_home()) ? false : get_permalink();
?>

			<ul class="dropdown-menu">
			<?php 
				$q1 = "SELECT * FROM `permissions` WHERE type = 'view' AND user = '".$GLOBALS['user']."'";
				$r1 = $GLOBALS['mysqli']->query($q1);
				$count = $r1->num_rows;
				if ($count >= 1){
					echo "<li style='font-weight: bold; padding-left: 20px;'>Available Vaults</li>";
				}
				while ($row = $r1->fetch_assoc()) {
					$q2 = "SELECT * FROM `vaults` WHERE `vaultID` = '".$row['vaultID']."'";
					$r2 = $GLOBALS['mysqli']->query($q2);
					$row2 = $r2->fetch_assoc();
					echo "<li style='padding-left: 10px;'><a href='/treasury.php?tId=".$row['vaultID']."'>".$row2['vaultName']."</a></li>";
				}
				if($GLOBALS['user'] == $GLOBALS['vOwner']){ 
			?>
					<li><a href="treasurySettings.php">Settings</a></li>
					<li><a href="treasuryPermissions.php">Permissions</a></li>
					<li><a href="treasuryLog.php">Logs</a></li>
			<?php 
				} 
			?>
				<li><a href="<?php echo wp_logout_url($redirect); ?>">Logout</a></li>
            </ul>
<?php } else {   ?>
    <!-- here is a paragraph that is shown to anyone not logged in -->
			<ul class="dropdown-menu">
				<li><a href="/tplogin/?action=register">Sign Up</a></li>
				<li class="loginDiv">
					<div style="padding: 15px; padding-bottom: 0px;" >
						<input id="user_username" style="margin-bottom: 15px;" type="text" name="user_username" placeholder="Username" size="30" />
						<input id="user_password" placeholder="Password" style="margin-bottom: 15px;" type="password" name="user_password" size="30" />
						<input id="user_remember_me" style="float: left; margin-right: 10px;" type="checkbox" name="user_remember_me" value="1" />
						<label class="string optional" for="user_remember_me"> Remember me</label>
						<button class="btn btn-primary" style="clear: left; width: 100%; height: 32px; font-size: 13px;" name="commit" id="commit">Sign In</button>
					</div>
				</li>
            </ul>

<?php } ?>
		 </li>

      </ul>
   </div>
</div>