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
	<script type="text/javascript" src="scripts/settings.js"></script>
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
				<span class="panel-title">Theme</span>
				<div class="pull-right">
					<button type='button' title="Minimize Settings" class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
				</div>
			</div>
            <div class="panel-body">
				<label>Treasury Theme</label>
				<div class="input-group">
					<select id="cssChange" class="form-control">
						<?php getCssSetting(); ?>
					</select>
					<span class="input-group-btn">
						<button class="btn btn-info" id="saveCSS" type="button">Save</button>
					</span>
				</div>
			</div>
		</div>
        <div class="panel panel-primary">
            <div class="panel-heading">
				<span class="panel-title">Currency</span>
				<div class="pull-right">
					<button type='button' title="Minimize Settings" class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
				</div>
			</div>
            <div class="panel-body">
				<div class='row'>
					<div class='col-xs-4'><strong>Currency Name</strong></div>
					<div class='col-xs-4'><strong>Abbreviation</strong></div>
					<div class='col-xs-4'><strong>Multiplier</strong></div>
				</div>
				<div class="currencySettings">
				<?php getCurrencySettings(); ?>
				</div>
				<div class='row' style="margin-top: 5px; margin-bottom: 10px;">
					<div class="col-xs-12" style="text-align: center; margin: 0 auto;">
						<button type='submit' class='createCurrency btn btn-primary btn-lg'>Manage Currencies</button>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<label for="baseCurrency" title="Lowest form of currency in use">Base Currency:<select id="baseCurrency"><?php baseCurrencyList(); ?></select></label>
					</div>
					<div class="col-xs-6">
						<label for="commonCurrency" title="Most common curency that prices are based upon">Common Currency:<select id="commonCurrency"><?php commonCurrencyList(); ?></select></label>
					</div>
				</div>

			</div>
		</div>
        <div class="panel panel-primary">
            <div class="panel-heading">
				<span class="panel-title">Markup/Discount</span>
				<div class="pull-right">
					<button type='button' title="Minimize Settings" class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
				</div>
			</div>
            <div class="panel-body">
				<form class="form-horizontal" role="form">
				<div class="form-group">
					<label class="col-xs-5">Item Buy Markup/Discount</label>
					<div class="col-xs-7">
						<div class="input-group">
							<span class="input-group-addon">%</span>
							<input id="itemMarkup" name="itemMarkup" type="text" class="form-control" value="<?php echo $GLOBALS['ibMarkup']; ?>">
							<span class="input-group-btn">
								<button class="btn btn-info saveMarkup" rel="ibMarkup" type="button">Save</button>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group" title="This number should always be a positive number, a negative would give the party MORE money than the item is worth when selling.">
					<label class="col-xs-5">Item Sell Markup/Discount</label>
					<div class="col-xs-7">
						<div class="input-group">
							<span class="input-group-addon">%</span>
							<input id="itemMarkup" name="itemMarkup" type="text" class="form-control" value="<?php echo $GLOBALS['isMarkup']; ?>">
							<span class="input-group-btn">
								<button class="btn btn-info saveMarkup" rel="isMarkup" type="button">Save</button>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-5">Gems, Jewelry &amp; Art Buy Markup/Discount</label>
					<div class="col-xs-7">
						<div class="input-group">
							<span class="input-group-addon">%</span>
							<input id="itemMarkup" name="itemMarkup" type="text" class="form-control" value="<?php echo $GLOBALS['gbMarkup']; ?>">
							<span class="input-group-btn">
								<button class="btn btn-info saveMarkup" rel="gbMarkup" type="button">Save</button>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group" title="This number should always be a positive number, a negative would give the party MORE money than the item is worth when selling.">
					<label class="col-xs-5">Gems, Jewelry &amp; Art Sell Markup/Discount</label>
					<div class="col-xs-7">
						<div class="input-group">
							<span class="input-group-addon">%</span>
							<input id="itemMarkup" name="itemMarkup" type="text" class="form-control" value="<?php echo $GLOBALS['gsMarkup']; ?>">
							<span class="input-group-btn">
								<button class="btn btn-info saveMarkup" rel="gsMarkup" type="button">Save</button>
							</span>
						</div>
					</div>
				</div>
			</div>
        </div>

    </div>

    <div class="col-xs-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
				<span class="panel-title">News</span>
				<div class="pull-right">
					<button type='button' title="Minimize News" class='minimize btn btn-success'><span class='glyphicon glyphicon-chevron-up'></span></button>
				</div>		
			</div>
            <div class="panel-body">

			</div>
		</div>
	<?php } ?>
	</div>
<?php
		}
	} else {   ?>
    <!-- here is the jumbotron that is shown to anyone not logged in -->

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