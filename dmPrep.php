<?php 
	global $current_user, $user, $vault,$vName,$vOwner;
	require('wp-load.php');
	get_currentuserinfo();
	$GLOBALS['user'] = $current_user->ID;

?>
<!DOCTYPE html>
<html>
<head>
    <?php 
		INCLUDE 'functions/treasureF.php'; 
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Party Treasury<?php if ( is_user_logged_in() && $vcnt >= 1 ) { echo "&nbsp;-&nbsp;"; echo $GLOBALS['vName']; } ?> </title>
    <meta name="author" content="Lee Vaughan" />

    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap-switch.css" rel="stylesheet" type="text/css" />
	<link href="css/<?php if($GLOBALS['vTheme'] != ""){ echo $GLOBALS['vTheme'];} else { echo "default.css";} ?>" id="style" rel="stylesheet" type="text/css" />
    <link href="css/main.css" rel="stylesheet" type="text/css" />
    <script src="scripts/jquery-2.1.0.min.js"></script>
    <script src="scripts/jquery-ui-1.10.4.custom.min.js"></script>
    <script src="scripts/bootstrap.min.js"></script>
    <script src="scripts/bootstrap-switch.min.js"></script>
	<script src="scripts/jquery.chained.remote.min.js"></script>
	<script type="text/javascript" src="scripts/main.js"></script>

	<script type="text/javascript" src="scripts/dm.js"></script>
</head>
<body>
<div id="container-fluid">
<?php if ( is_user_logged_in() && $GLOBALS['memberLevel'] >= 1 && $GLOBALS['memberStatus'] == 'active') { 
	if($vcnt == 0 && $GLOBALS['memberLevel'] > 1) {
?>
	<div class="row">
		<div class="col-xs-10 col-xs-offset-1">
			<div class="jumbotron"><h2>You do not have a treasury!</h2><h3>Define your treasury now:</h3><div class="form-group"><input type="text" placeholder="Vault Name" class="form-control" id="vaultName" /><br/><input type="button" value="Create Vault" id="createVault" class="form-control btn btn-primary" /></div>
		</div>
	</div>
<?	} else { ?>
    <!-- text that logged in users will see -->
	<div class="row">
		<div class="col-xs-6">
			<ul class="prepListGroup">
			<?php getPrepList(); ?>
			</ul>
		</div>
		<div class="col-xs-6">
			<div class="input-group"><button class="btn btn-success form-control" id="createTreasure">Create Prepared Treasure</button></div>
			<div class="input-groupw"><button class="btn btn-success form-control" id="transferTreasure">Transfer Selected Treasure</button></div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<span class="panel-title">Prepared Treasure List</span>
					<div class="pull-right">
						<button type='button' class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
					</div>
				</div>
				<div class="panel-body">
					<ul class="list-group prepItems">		</ul>
				</div>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<span class="panel-title">Add Currency</span>
					<div class="pull-right">
						<button type='button' class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
					</div>		
				</div>
				<div class="panel-body">
					<form>
						<?php coinAddBlock(); ?>
					</form>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<span class="panel-title">Add Gems, Jewelry &amp; Art</span>
					<div class="pull-right">
						<span class="label label-success gemLabel" style="display: none;" title="<?php if($GLOBALS['gbMarkup']>=0){echo "Markup";} else{ echo "Discount";}?> on Purchases">Buy: <?php echo $GLOBALS['gbMarkup']; ?>%</span>
						<input type="checkbox" name="gjaSwitch" id="gjaSwitch" value="1">
						<button type='button' class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
					</div>		
				</div>
				<div class="panel-body">
					<div class="input-group">
						<select class="form-control" id="gjaLoc">
							<option value="0">Select a location</option>
							<?php 
								$qLoc = "SELECT * FROM `bags` WHERE vaultID = '".$GLOBALS['vault']."'";
								$result = $GLOBALS['mysqli']->query($qLoc);
								while ($row = $result->fetch_assoc()) {
									echo "<option value='".$row['bagID']."'>".$row['bagName']."</option>";
								}
							?>
						</select>
						<span class="input-group-btn">
							<button class="btn btn-success editBag" type="button">Edit</button>
						</span>
					</div>
					<div class="form-group col-xs-4">
						<input type="text" id="gjaName" class="form-control" name="itemName" placeholder="Name">
					</div><!-- /input-group -->
					<div class="form-group col-xs-4">
							<input type="text" class="form-control" id="gjaValue" placeholder="Value in <?php echo $GLOBALS['curAbrev'];?>">
					</div>
					<div class="form-group col-xs-4">
						<div class="input-group">
							<input type="text" class="form-control" id="gjaQty" placeholder="Quantity">
							<span class="input-group-btn">
								<button class="btn btn-success" id="addGJA" type="button">Add</button>
							</span>
						</div>
					</div>
					<div class="col-xs-8">
						<select id="gClass" name="gClass" class="form-control">
							<option selected="selected" value="">Select Quick Add Option</option>
							<option value="1">Gems</option>
							<option value="2">Jewelry</option>
							<option value="3">Art</option>
						</select>
						<select id="gType" name="gType" class="form-control">
							<option value="">--</option>
						</select>
						<select id="gName" name="gName" class="form-control">
							<option value="">--</option>
						</select>
					</div>
					<div class="col-xs-4">
						<button class="btn btn-success" id="quickGJA" type="button">Add</button>
					</div>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<span class="panel-title">Add Items &amp; Equipment</span>
					<div class="pull-right">
						<span class="label label-success itemLabel" style="display: none;" title="<?php if($GLOBALS['ibMarkup']>=0){echo "Markup";} else{ echo "Discount";}?> on Purchases">Buy: <?php echo $GLOBALS['ibMarkup']; ?>%</span>
						<input type="checkbox" name="itemSwitch" id="itemSwitch" value="1">
						<button type='button' class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
					</div>
				</div>
				<div class="panel-body">
					<div class="input-group">
						<select class="form-control" id="itemLoc">
							<option value="0" selected="selected">Select a location</option>
							<?php 
								$qLoc = "SELECT * FROM `bags` WHERE vaultID = '".$GLOBALS['vault']."'";
								$result = $GLOBALS['mysqli']->query($qLoc);
								while ($row = $result->fetch_assoc()) {
									echo "<option value='".$row['bagID']."'>".$row['bagName']."</option>";
								}
							?>
						</select>
						<span class="input-group-btn">
							<button class="btn btn-success editBag" type="button">Edit</button>
						</span>
					</div>
					<div class="form-group col-xs-4">
						<div class="input-group">
							<input type="text" id="itemName" class="form-control" name="itemName" placeholder="Name">
						</div>
					</div><!-- /input-group -->
					<div class="form-group col-xs-4">
						<div class="input-group">
							<input type="text" class="form-control" id="itemValue" placeholder="Value in <?php echo $GLOBALS['curAbrev'];?>">
						</div>
					</div>
					<div class="form-group col-xs-4">
						<div class="input-group">
							<input type="text" class="form-control" id="itemQty" placeholder="Quantity">
							<span class="input-group-btn">
								<button class="btn btn-success" id="addItem" data="Add" type="button">Add</button>
							</span>
						</div>
					</div>
					<br />
					<div class="col-xs-8">
							<select id="qClass" name="qClass" class="form-control">
								<option value="0" selected="selected">Select Quick Add Option</option>
								<option value="1">Armor</option>
								<option value="2">Weapons</option>
								<option value="3">Equipment</option>
								<option value="4">Magic Items</option>
							</select>
							<select id="qType" name="qType" class="form-control">
								<option value="">--</option>
							</select>
							<select id="qName" name="qName" class="form-control">
								<option value="">--</option>
							</select>
					</div>
					<div class="col-xs-4">
								<select multiple id="qMod" name="qMod" class="form-control" size="3">
									<option value="">--</option>
								</select>
								<button class="btn btn-success form-control" id="quickItem" type="button">Add</button>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}
	} else {   ?>
    <!-- here is a paragraph that is shown to anyone not logged in -->

	<div class="row">
		<div class="col-xs-10 col-xs-offset-1">
			<div class="jumbotron">
				<p><h1>You are not <a href="<?php home_url(); ?>/tplogin/">logged in.</a></h1><br /><a href="<?php home_url(); ?>/tplogin/">Login</a> now to access to an existing treasury.</p>
				<p><a href="<?php home_url(); ?>/tplogin/?action=register">Register</a> now to have access to an existing treasury or create your own.</p></div>
				<?php echo is_user_logged_in(); echo $GLOBALS['memberLevel']; echo $GLOBALS['memberStatus']; ?>
			</div>
		</div>
	</div>


<?php } ?>
</div>
<?php INCLUDE 'includes/topbar.php'; ?>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Treasure Split</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="user" value="<?php echo $current_user->ID; ?>" />
<input type="hidden" id="curMult" value="<?php echo $GLOBALS['curMult']; ?>" />
</body>

<?php 
/* close connection */
$mysqli->close();
?>
</html>
