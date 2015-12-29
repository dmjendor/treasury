<?php 
	global $mysqli;
	define("DB_HOST","mysql.partytreasury.com");
	define("DB_USER","ptdbdnd");
	define("DB_PASSWORD", "Z9jC7G_Xtj9S!9G");
	define("DB_DATABASE", "dndptdb");

	$GLOBALS['mysqli'] = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

	$qLoc = "SELECT * FROM `vaults` WHERE owner = '".$GLOBALS['user']."'";
	$result = $GLOBALS['mysqli']->query($qLoc);	
	$vcnt = mysqli_num_rows($result);
	if($vcnt >= 1){
		$row = $result->fetch_assoc();
		$GLOBALS['vault'] = $row['vaultID'];
		$GLOBALS['vName'] = $row['vaultName'];
	}
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}


	function getCoinPerms(){
		$q = "SELECT * FROM `permissions` WHERE type = 'coin' AND vaultID = '".$GLOBALS['vault']."'";
		$result = $GLOBALS['mysqli']->query($q);
		while ($row = $result->fetch_assoc()) {
			$q2 = "SELECT display_name FROM `tpwp_users` WHERE ID = '".$row['user']."'";
			$r2 = $GLOBALS['mysqli']->query($q2);
			$user = $r2->fetch_assoc();
			echo "<li class='list-group-item'><b>".$user['display_name']."</b>";
			if($row['user'] != $GLOBALS['user']){
				echo "<button type='button' class='delPerm btn btn-danger btn-sm pull-right' rel='coin' id='".$i."s' title='Remove Permission' value='".$row['permID']."'>
								<span class='glyphicon glyphicon-minus'></span>
							</button>";
			}			
			echo "</li>";
		}
	}

	function getGJAPerms(){
		$q = "SELECT * FROM `permissions` WHERE type = 'gja' AND vaultID = '".$GLOBALS['vault']."'";
		$result = $GLOBALS['mysqli']->query($q);
		while ($row = $result->fetch_assoc()) {
			$q2 = "SELECT display_name FROM `tpwp_users` WHERE ID = '".$row['user']."'";
			$r2 = $GLOBALS['mysqli']->query($q2);
			$user = $r2->fetch_assoc();
			echo "<li class='list-group-item'><b>".$user['display_name']."</b>";
			if($row['user'] != $GLOBALS['user']){
				echo "<button type='button' class='delPerm btn btn-danger btn-sm pull-right' rel='gja' id='".$i."s' title='Remove Permission' value='".$row['permID']."'>
								<span class='glyphicon glyphicon-minus'></span>
							</button>";
			}			
			echo "</li>";
		}
	}

	function getItemPerms(){
		$q = "SELECT * FROM `permissions` WHERE type = 'item' AND vaultID = '".$GLOBALS['vault']."'";
		$result = $GLOBALS['mysqli']->query($q);
		while ($row = $result->fetch_assoc()) {
			$q2 = "SELECT display_name FROM `tpwp_users` WHERE ID = '".$row['user']."'";
			$r2 = $GLOBALS['mysqli']->query($q2);
			$user = $r2->fetch_assoc();
			echo "<li class='list-group-item'><b>".$user['display_name']."</b>";
			if($row['user'] != $GLOBALS['user']){
				echo "<button type='button' class='delPerm btn btn-danger btn-sm pull-right' rel='item' id='".$i."s' title='Remove Permission' value='".$row['permID']."'>
								<span class='glyphicon glyphicon-minus'></span>
							</button>";
			}			
			echo "</li>";
		}
	}

	function getViewPerms(){
		$q = "SELECT * FROM `permissions` WHERE type = 'view' AND vaultID = '".$GLOBALS['vault']."'";
		$result = $GLOBALS['mysqli']->query($q);
		while ($row = $result->fetch_assoc()) {
			$q2 = "SELECT display_name FROM `tpwp_users` WHERE ID = '".$row['user']."'";
			$r2 = $GLOBALS['mysqli']->query($q2);
			$user = $r2->fetch_assoc();
			echo "<li class='list-group-item'><b>".$user['display_name']."</b>";
			if($row['user'] != $GLOBALS['user']){
				echo "<button type='button' class='delPerm btn btn-danger btn-sm pull-right' rel='view' id='".$i."s' title='Remove Permission' value='".$row['permID']."'>
								<span class='glyphicon glyphicon-minus'></span>
							</button>";
			}			
			echo "</li>";
		}
	}
?>