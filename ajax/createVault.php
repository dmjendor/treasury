<?php
	global $current_user, $user, $vault;
	require('../wp-load.php');
	get_currentuserinfo();
	$GLOBALS['user'] = $current_user->ID;
	INCLUDE '../functions/treasureF.php';

	if(!empty($_POST['value'])) {
		$tVal = $_POST['value'];
		$query = "INSERT INTO vaults ( vaultName, owner ) VALUE ('".$tVal."', '".$GLOBALS['user']."')";
		$result = $GLOBALS['mysqli']->query($query);
		$vID = $GLOBALS['mysqli']->insert_id;

		$q3 = "INSERT INTO `permissions` (`user`,`type`,`vaultID`) VALUES ('".$GLOBALS['user']."','coin','".$vID."'),('".$GLOBALS['user']."','gja','".$vID."'),('".$GLOBALS['user']."','item','".$vID."'),('".$GLOBALS['user']."','view','".$vID."')";
		$r3 = $GLOBALS['mysqli']->query($q3);
	}
	
/* close connection */
$mysqli->close();
?>
