<?php 
	global $current_user, $user, $vault,$vName;
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
	<link href="css/<?php echo $GLOBALS['vTheme']; ?>" id="style" rel="stylesheet" type="text/css" />
    <link href="css/main.css" rel="stylesheet" type="text/css" />
    <script src="scripts/jquery-2.1.0.min.js"></script>
    <script src="scripts/jquery-ui-1.10.4.custom.min.js"></script>
    <script src="scripts/bootstrap.min.js"></script>
    <script src="scripts/bootstrap-switch.min.js"></script>
	<script type="text/javascript" src="scripts/main.js"></script>
	<script type="text/javascript" src="scripts/perms.js"></script>
</head>
<body>
<div id="container-fluid">
<?php if ( is_user_logged_in() && $GLOBALS['memberLevel'] >= 1 && $GLOBALS['memberStatus'] == 'active') { 
	if($GLOBALS['vOwner'] != $GLOBALS['user']) {
		http_redirect("http://www.partytreasury.com/treasury.php");
	} else { ?>
    <!-- text that logged in users will see -->
	<?php if($GLOBALS['vOwner'] == $GLOBALS['user']){ ?>
	<div class="col-xs-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
				<span class="panel-title">View Permissions</span>
				<div class="pull-right">
					<button type='button' title="Minimize View Permissions" class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
					<button type='button' class='refresh btn btn-success' rel='viewPerms' ref="getViewPerms"><span class='glyphicon glyphicon-refresh'></span></button>
				</div>
			</div>
            <div class="panel-body">
				<ul class="list-group" id="viewPermList">
					<?php getViewPerms(); ?>
				</ul>
            </div>
        </div>
		<div class="panel panel-primary">
            <div class="panel-heading">
				<span class="panel-title">Coin Permissions</span>
				<div class="pull-right">
					<button type='button' title="Minimize Coin Permissions" class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
					<button type='button' class='refresh btn btn-success' rel='coinPermList' ref="getCoinPerms"><span class='glyphicon glyphicon-refresh'></span></button>
				</div>
			</div>
            <div class="panel-body">
				<ul class="list-group" id="coinPermList">
					<?php getCoinPerms(); ?>
				</ul>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
				<span class="panel-title">Gems, Jewelry, &amp; Art Permissions</span>
				<div class="pull-right">
					<button type='button' title="Minimize Gems, Jewelry &amp; Art Permissions" class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
					<button type='button' ref='getGJAPerms' rel='gjaPermList' class='refresh btn btn-success'><span class='glyphicon glyphicon-refresh'></span></button>
				</div>			
			</div>
            <div class="panel-body">
				<ul class="list-group" id="gjaPermList">
				<?php getGJAPerms(); ?>
				</ul>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
				<span class="panel-title">Items &amp; Equipment Permissions</span>
				<div class="pull-right">
					<button type='button' title="Minimize Item &amp; Equipment Permissions" class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
					<button type='button' ref='getItemPerms' rel='itemPermList' class='refresh btn btn-success'><span class='glyphicon glyphicon-refresh'></span></button>
				</div>			
			</div>
            <div class="panel-body" id="itemList">
				<ul class="list-group" id="itemPermList">
				<?php getItemPerms(); ?>
				</ul>
            </div>
        </div>

    </div>

    <div class="col-xs-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
				<span class="panel-title">Add View Permissions</span>
				<div class="pull-right">
					<button type='button' title="Minimize Add View Permissions" class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
				</div>		
			</div>
            <div class="panel-body">
				<div class="input-group">
					<input type="text" class="form-control" id="viewPermUser" placeholder="Username or email address">
					<span class="input-group-btn">
						<button class="btn btn-success addPerm" rel="view" type="button">Add</button>
					</span>
				</div>
				<div class="checkbox"><label for="addAll">Add All Permissions<input type="checkbox" id="addAll" name="addAll" value="1" /></label></div>
				<p>Adding any other permission will also automatically add a View permission.</p>
			</div>
		</div>
		<div class="panel panel-primary">
            <div class="panel-heading">
				<span class="panel-title">Add Coin Permissions</span>
				<div class="pull-right">
					<button type='button' title="Minimize Add Coin Permissions" class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
				</div>		
			</div>
            <div class="panel-body">
				<div class="input-group">
					<input type="text" class="form-control" id="coinPermUser" placeholder="Username or email address">
					<span class="input-group-btn">
						<button class="btn btn-success addPerm" rel="coin" type="button">Add</button>
					</span>
				</div>
			</div>
		</div>
		<div class="panel panel-primary">
			<div class="panel-heading">
				<span class="panel-title">Add Gems, Jewelry, &amp; Art Permissions</span>
				<div class="pull-right">
					<button type='button' title="Minimize Gems, Jewelry &amp; Art Permissions" class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
				</div>		
			</div>
			<div class="panel-body">
				<div class="input-group">
					<input type="text" class="form-control" id="gjaPermUser" placeholder="Username or email address">
					<span class="input-group-btn">
						<button class="btn btn-success addPerm" rel="gja" type="button">Add</button>
					</span>
				</div>
			</div>
		</div>
		<div class="panel panel-primary">
			<div class="panel-heading">
				<span class="panel-title">Add Items &amp; Equipment Permissions</span>
				<div class="pull-right">
					<button type='button' title="Minimize Add Item &amp; Equipment Permissions" class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
				</div>
			</div>
			<div class="panel-body">
				<div class="input-group">
					<input type="text" class="form-control" id="itemPermUser" placeholder="Username or email address">
					<span class="input-group-btn">
						<button class="btn btn-success addPerm" rel="item" type="button">Add</button>
					</span>
				</div>
			</div>
		</div>
	<?php } ?>
	</div>
<?php
		}
	} else {   ?>
    <!-- here is a paragraph that is shown to anyone not logged in -->

	<div class="row">
		<div class="col-xs-10 col-xs-offset-1">
			<div class="jumbotron"><p><h1>You are not currently logged in.</h1><br /><a href="<?php home_url(); ?>/tplogin/?action=register">Register</a> now to have access to an existing treasury or create your own.</p></div>
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
</body>

<?php 
/* close connection */
$mysqli->close();
?>
</html>