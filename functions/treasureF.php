<?php
	global $mysqli,$coinPerm,$gjaPerm,$itemPerm;
	define("DB_HOST","mysql.partytreasury.com");
	define("DB_USER","ptdbdnd");
	define("DB_PASSWORD", "Z9jC7G_Xtj9S!9G");
	define("DB_DATABASE", "dndptdb");

	$GLOBALS['coinPerm'] = $GLOBALS['gjaPerm'] = $GLOBALS['itemPerm'] = FALSE;

	$GLOBALS['mysqli'] = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	if(isset($_GET['tId'])){
		$qLoc = "SELECT * FROM `vaults` WHERE vaultID = ?";
		$stmt = $GLOBALS['mysqli']->prepare($qLoc);
		$stmt->bind_param('i',$_GET['tId']);
		$stmt->execute();
		$stmt->bind_result($vaultID,$vaultName,$baseCurID,$cmCurID,$owner,$theme,$ibMarkup, $isMarkup, $gbMarkup, $gsMarkup, $cSplit);
		$stmt->store_result();
		$vcnt = $stmt->num_rows;
		if($stmt->num_rows >= 1){ 
			while ($stmt->fetch()){
				$GLOBALS['vault'] = $vaultID;
				$GLOBALS['vName'] = $vaultName;
				$GLOBALS['vOwner'] = $owner;
				$GLOBALS['vTheme'] = $theme;
				$GLOBALS['basCurID'] = $baseCurID;
				$GLOBALS['comCurID'] = $cmCurID;
				$GLOBALS['ibMarkup'] = $ibMarkup;
				$GLOBALS['isMarkup'] = $isMarkup;
				$GLOBALS['gbMarkup'] = $gbMarkup;
				$GLOBALS['gsMarkup'] = $gsMarkup;
				$GLOBALS['cSplit'] = $cSplit;
			}
		}
	} else {
		$qLoc = "SELECT * FROM `vaults` WHERE owner = '".$GLOBALS['user']."'";
		$result = $GLOBALS['mysqli']->query($qLoc);
		$vcnt = mysqli_num_rows($result);
		if($vcnt >= 1){
			$row = $result->fetch_assoc();
			$GLOBALS['vault'] = $row['vaultID'];
			$GLOBALS['vName'] = $row['vaultName'];
			$GLOBALS['vOwner'] = $row['owner'];
			$GLOBALS['vTheme'] = $row['theme'];
			$GLOBALS['basCurID'] = $row['baseCurID'];
			$GLOBALS['comCurID'] = $row['cmCurID'];
			$GLOBALS['ibMarkup'] = $row['ibMarkup'];
			$GLOBALS['isMarkup'] = $row['isMarkup'];
			$GLOBALS['gbMarkup'] = $row['gbMarkup'];
			$GLOBALS['gsMarkup'] = $row['gsMarkup'];
			$GLOBALS['cSplit'] = $row['cSplit'];
		}		
	}
	/* Check user permissions */
	$p1 = "SELECT * FROM `permissions` WHERE user = '".$GLOBALS['user']."' AND vaultID = '".$GLOBALS['vault']."'";
	$pR1 = $GLOBALS['mysqli']->query($p1);
	while($row = $pR1->fetch_assoc()){
		if($row['type'] == 'view'){ $GLOBALS['viewPerm'] = TRUE; }
		if($row['type'] == 'coin'){ $GLOBALS['coinPerm'] = TRUE; }
		if($row['type'] == 'gja'){ $GLOBALS['gjaPerm'] = TRUE; }
		if($row['type'] == 'item'){ $GLOBALS['itemPerm'] = TRUE; }
	}

	/* Get currencies */
	if($GLOBALS['comCurID'] == 0){
		$GLOBALS['curAbrev'] = 'gp';
		$GLOBALS['curMult'] = '100';
		$GLOBALS['curName'] = 'gold';
	} else {
		$c1 = "SELECT * FROM `vaultCurrency` WHERE vaultID = '".$GLOBALS['vault']."' AND vcID = '".$GLOBALS['comCurID']."'";
		$cR1 = $GLOBALS['mysqli']->query($c1);
		$row = $cR1->fetch_assoc();
		$GLOBALS['curAbrev'] = $row['abbrev'];
		$GLOBALS['curMult'] = $row['multiplier'];
		$GLOBALS['curName'] = $row['currencyName'];

	}

	$qS = "SELECT membership_id, status FROM `tpwp_pmpro_memberships_users` WHERE user_id = '".$GLOBALS['user']."' ORDER BY `id` DESC LIMIT 1";
	$sR = $GLOBALS['mysqli']->query($qS);
	$row = $sR->fetch_assoc();
	$GLOBALS['memberLevel'] = $row['membership_id'];
	$GLOBALS['memberStatus'] = $row['status'];


	INCLUDE 'items.php';
	INCLUDE 'gems.php';
	INCLUDE 'coin.php';
	INCLUDE 'bags.php';
	INCLUDE 'logs.php';
	INCLUDE 'permissions.php';
	INCLUDE 'settings.php';
	INCLUDE 'perms.php';
	INCLUDE 'dm.php';

	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

?>