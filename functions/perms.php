<?php 
	function addPerm($user){
		$stmt = $GLOBALS['mysqli']->stmt_init();
		if(strpos($user[0],"@") == FALSE){
			$qu = "SELECT ID FROM `tpwp_users` WHERE `user_login` = ?";
		} else {
			$qu = "SELECT ID FROM `tpwp_users` WHERE `user_email` = ?";
		}
		$stmt = $GLOBALS['mysqli']->prepare($qu);
		$stmt->bind_param('s',$user[0]);
		$stmt->execute();
		$stmt->bind_result($ID);
		$stmt->store_result();
		$dup = FALSE;
		if($stmt->num_rows == 1){ 
			while ($stmt->fetch()){
				$dEx = "SELECT count(*) FROM `permissions` WHERE user = '".$ID."' AND type = '".$user[1]."' AND vaultID = '".$GLOBALS['vault']."'";
				$rEx = $GLOBALS['mysqli']->query($dEx);
				$dCount = mysqli_fetch_row($rEx);
				if($dCount[0]==0){
					$q1 = "INSERT INTO `permissions` (`user`,`type`,`vaultID`) VALUES ('".$ID."','".$user[1]."','".$GLOBALS['vault']."')";
					$r1 = $GLOBALS['mysqli']->query($q1);
					$q2 = "INSERT INTO `perm_log` (`user`,`type`,`changeBy`, `vaultID`) VALUES ('".$ID."','".$user[1]."','".$GLOBALS['user']."', '".$GLOBALS['vault']."')";
					$r2 = $GLOBALS['mysqli']->query($q2);
					if(isset($user[2])){
						if($user[2] == 1){
							$q3 = "INSERT INTO `permissions` (`user`,`type`,`vaultID`) VALUES ('".$ID."','coin','".$GLOBALS['vault']."'),('".$ID."','gja','".$GLOBALS['vault']."'),('".$ID."','item','".$GLOBALS['vault']."')";
							$r3 = $GLOBALS['mysqli']->query($q3);
							$q4 = "INSERT INTO `perm_log` (`user`,`type`,`changeBy`, `vaultID`) VALUES ('".$ID."','coin','".$GLOBALS['user']."', '".$GLOBALS['vault']."'),('".$ID."','gja','".$GLOBALS['user']."', '".$GLOBALS['vault']."'),('".$ID."','item','".$GLOBALS['user']."', '".$GLOBALS['vault']."')";
							$r4 = $GLOBALS['mysqli']->query($q4);
						}
					}
				} else { 
					$dup = TRUE;
				}
			}
				$qEx = "SELECT count(*) FROM `permissions` WHERE user = '".$ID."' AND type = 'view' AND vaultID = '".$GLOBALS['vault']."'";
				$rEx = $GLOBALS['mysqli']->query($qEx);
				$rCount = mysqli_fetch_row($rEx);
				if($rCount[0]==0){
					$qV = "INSERT INTO `permissions` (`user`,`type`,`vaultID`) VALUES ('".$ID."','view','".$GLOBALS['vault']."')";
					$rV = $GLOBALS['mysqli']->query($qV);
					$qVl = "INSERT INTO `perm_log` (`user`,`type`,`changeBy`,`vaultID`) VALUES ('".$ID."','view','".$GLOBALS['user']."','".$GLOBALS['vault']."')";
					$rVl = $GLOBALS['mysqli']->query($qVl);
				}
		} else { 
			echo "fail";
		}
		$stmt->free_result();
		if($dup == TRUE){
			echo "dup";
		} else {
			echo $user[1];
		}
	}

	function getPerms($item){
		$q = "SELECT * FROM `permissions` WHERE type = '".$item."' AND vaultID = '".$GLOBALS['vault']."'";
		$result = $GLOBALS['mysqli']->query($q);
		while ($row = $result->fetch_assoc()) {
			$q2 = "SELECT display_name FROM `tpwp_users` WHERE ID = '".$row['user']."'";
			$r2 = $GLOBALS['mysqli']->query($q2);
			$user = $r2->fetch_assoc();
			echo "<li class='list-group-item'><b>".$user['display_name']."</b>";
			if($row['user'] != $GLOBALS['user']){
				echo "<button type='button' class='delPerm btn btn-danger btn-sm pull-right' rel='".$item."' id='".$i."s' title='Remove Permission' value='".$row['permID']."'>
								<span class='glyphicon glyphicon-minus'></span>
							</button>";
			}			
			echo "</li>";
		}
	}

	function delPerm($user){
		$qR = "SELECT * FROM `permissions` WHERE `permID` = '".$user."'";
		$rR = $GLOBALS['mysqli']->query($qR);
		while( $row = $rR->fetch_assoc() ) {
			$q1 = "DELETE FROM `permissions` WHERE `permID` = '".$user."'";
			$r1 = $GLOBALS['mysqli']->query($q1);
			$q2 = "INSERT INTO `perm_log` (`user`,`type`,`changeBy`, `vaultID`, `delete`) VALUES ('".$row['user']."','".$row['type']."','".$GLOBALS['user']."', '".$GLOBALS['vault']."','1')";
			$r2 = $GLOBALS['mysqli']->query($q2);
			echo $row['type'];
		}
	}
?>