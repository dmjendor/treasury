<?php 
	global $display_name , $user_email;
	require('wp-load.php');
	get_currentuserinfo();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Party Treasury</title>
    <meta name="author" content="Lee Vaughan" />
    <?php INCLUDE 'functions/treasureF.php'; ?>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap-switch.css" rel="stylesheet" type="text/css" />
    <link href="css/main.css" rel="stylesheet" type="text/css" />
    <script src="scripts/jquery-2.1.0.min.js"></script>
    <script src="scripts/bootstrap.min.js"></script>
    <script src="scripts/bootstrap-switch.min.js"></script>
	<script src="scripts/jquery.chained.remote.min.js"></script>
	<script type="text/javascript" src="scripts/main.js"></script>
</head>
<body>
<div id="container-fluid">
<?php if ( is_user_logged_in() ) { ?>
    <!-- text that logged in users will see -->
	<div class="col-xs-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
				<span class="panel-title">Coin</span>
				<div class="pull-right">
					<button type='button' class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
					<button type='button' class='refresh btn btn-success' ref="partyCoin"><span class='glyphicon glyphicon-refresh'></span></button>
				</div>
			</div>
            <div class="panel-body">
				<ul class="list-group" id="partyCoin">
					<li class='list-group-item'><b>Platinum: </b><span class='badge'><?php getPt(); ?></span></li>
					<li class='list-group-item'><b>Gold: </b><span class='badge'><?php getAu(); ?></span></li>
					<li class='list-group-item'><b>Silver: </b><span class='badge'><?php getAg(); ?></span></li>
					<li class='list-group-item'><b>Copper: </b><span class='badge'><?php getCu(); ?></span></li>
				</ul>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
				<span class="panel-title">Gems, Jewelry, &amp; Art</span>
				<div class="pull-right">
					<button type='button' class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
					<button type='button' class='refresh btn btn-success'><span class='glyphicon glyphicon-refresh'></span></button>
				</div>			
			</div>
            <div class="panel-body" id="gjaList">
				<?php getGJA(); ?>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
				<span class="panel-title">Items &amp; Equipment</span>
				<div class="pull-right">
					<button type='button' class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
					<button type='button' class='refresh btn btn-success'><span class='glyphicon glyphicon-refresh'></span></button>
				</div>			
			</div>
            <div class="panel-body" id="itemList">
				<?php getItems(); ?>
            </div>
        </div>

    </div>

    <div class="col-xs-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
				<span class="panel-title">Add Coin</span>
				<div class="pull-right">
					<button type='button' class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
				</div>		
			</div>
            <div class="panel-body">
				<form>
					<div class="col-xs-6">
						<div class="input-group">
							<input type="text" class="form-control" id="platinum" name="platinum" placeholder="Platinum">
							<span class="input-group-btn">
								<button class="btn btn-success" id="ptBtn" type="button">Add</button>
							</span>
						</div><!-- /input-group -->

						<div class="input-group">
							<input type="text" class="form-control" id="gold" name="gold" placeholder="Gold">
							<span class="input-group-btn">
								<button class="btn btn-success" id="auBtn" type="button">Add</button>
							</span>
						</div><!-- /input-group -->
					</div>
					<div class="col-xs-6">
						<div class="input-group">
							<input type="text" class="form-control" id="silver" name="silver" placeholder="Silver">
							<span class="input-group-btn">
							<button class="btn btn-success" id="agBtn" type="button">Add</button>
							</span>
						</div><!-- /input-group -->

						<div class="input-group">
							<input type="text" class="form-control" id="copper" name="copper" placeholder="Copper">
							<span class="input-group-btn">
								<button class="btn btn-success" id="cuBtn" type="button">Add</button>
							</span>
						</div><!-- /input-group -->
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label for="partyNum">Party Split</label>
							<div class="input-group">
								<input type="text" class="form-control" id="partyNum" name="partyNum" placeholder="# in Party" />
								<span class="input-group-btn">
									<button id="split" name="split" class="btn btn-success">Split</button>
								</span>
							</div>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<div class="input-group">
								<label for="treasureInc" title="Includes the treasury as a party member and inserts a share back into the treasury.">Include Treasury in Split?</span>
								<input type="checkbox" data-size="normal" data-on-text="Yes" data-off-text="No" id="treasureInc" name="treasureInc" value="1" title="Includes the treasury as a party member and inserts a share back into the treasury." />
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="panel panel-primary">
			<div class="panel-heading">
				<span class="panel-title">Add Gems, Jewelry, &amp; Art</span>
				<div class="pull-right">
					<button type='button' class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
				</div>		
			</div>
			<div class="panel-body">
				<div class="input-group">
					<select class="form-control" id="gjaLoc">
						<option value="0" selected="selected">Select a location</option>
						<?php 
							$qLoc = "SELECT * FROM `bags`";
							$result = $GLOBALS['mysqli']->query($qLoc);
							while ($row = $result->fetch_assoc()) {
								echo "<option value='".$row['bagId']."'>".$row['bagName']."</option>";
							}
						?>
					</select>
					<span class="input-group-btn">
						<button class="btn btn-success editBag" type="button">Edit</button>
					</span>
				</div>
				<div class="form-group col-xs-4">
					<div class="input-group">
						<input type="text" id="gjaName" class="form-control" name="itemName" placeholder="Name">
					</div>
				</div><!-- /input-group -->
				<div class="form-group col-xs-4">
					<div class="input-group">
						<input type="text" class="form-control" id="gjaValue" placeholder="Value">
					</div>
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
						<button class="btn btn-success" id="quickGJA" type="button">Go</button>
					</div>
			</div>
		</div>
		<div class="panel panel-primary">
			<div class="panel-heading">
				<span class="panel-title">Add Items &amp; Equipment</span>
				<div class="pull-right">
					<button type='button' class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
				</div>
			</div>
			<div class="panel-body">
				<div class="input-group">
					<select class="form-control" id="itemLoc">
						<option value="0" selected="selected">Select a location</option>
						<?php 
							$qLoc = "SELECT * FROM `bags`";
							$result = $GLOBALS['mysqli']->query($qLoc);
							while ($row = $result->fetch_assoc()) {
								echo "<option value='".$row['bagId']."'>".$row['bagName']."</option>";
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
						<input type="text" class="form-control" id="itemValue" placeholder="Value">
					</div>
				</div>
				<div class="form-group col-xs-4">
					<div class="input-group">
						<input type="text" class="form-control" id="itemQty" placeholder="Quantity">
						<span class="input-group-btn">
							<button class="btn btn-success" id="addItem" type="button">Add</button>
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
							<button class="btn btn-success form-control" id="quickItem" type="button">Go</button>
				</div>
			</div>
		</div>
	</div>
<?php } else {   ?>
    <!-- here is a paragraph that is shown to anyone not logged in -->

<p><h1>You are not currently logged in.</h1><br />By <a href="<?php home_url(); ?>/tplogin/?action=register">registering</a>, you can save your favorite posts for future reference.</p>

<?php } ?>
</div>

<div class="navbar navbar-default navbar-fixed-top">
	<div class="navbar-header">
		<a class="navbar-brand" href="#">Party Treasury</a>
	</div>
<div>
      <ul class="nav navbar-nav pull-right">
         <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class='glyphicon glyphicon-cog'></span></a>
            <ul class="dropdown-menu">
				<li><a href="/users/sign_up">Sign Up</a></li>
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
         </li>
      </ul>
   </div>
</div>
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

</body>

<?php 
/* close connection */
$mysqli->close();
?>
</html>