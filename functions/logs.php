<?php

	function logDel($item){
		switch($item){
			case "coinDel":
				$qS = "DELETE FROM `coin` WHERE vaultID = '".$GLOBALS['vault']."' AND history = '1'";
				break;
			case "gjaDel":
				$qS = "DELETE FROM `gja_log` WHERE vaultID = '".$GLOBALS['vault']."'";
				break;
			case "itemDel":
				$qS = "DELETE FROM `treasure_log` WHERE vaultID = '".$GLOBALS['vault']."'";
				break;
			default:
				$qS = "DELETE FROM `coin` WHERE vaultID = '".$GLOBALS['vault']."' AND history = '1'";
				break;
		}
		$r2 = $GLOBALS['mysqli']->query($qS);
	}

	function coinHistory(){
		echo "		<table class='table table-bordered'>";
		echo "			<tr class='info'><td>User</td><td>Platinum</td><td>Gold</td><td>Silver</td><td>Copper</td><td>Time</td></tr>";
		$q1 = "SELECT * FROM `coin` WHERE vaultID = '".$GLOBALS['vault']."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		while ($bR = $r1->fetch_assoc()) {
			$i++;
			$q2 = "SELECT display_name FROM `tpwp_users` WHERE ID = '".$bR['changeBy']."'";
			$r2 = $GLOBALS['mysqli']->query($q2);
			$user = $r2->fetch_assoc();
			if($bR['history'] == 1){
				$class = "danger";
			} else {
				$class = "";
			}
			echo"	<tr class='".$class."'><td>".$user['display_name']."</td><td>".$bR['platinum']."</td><td>".$bR['gold']."</td><td>".$bR['silver']."</td><td>".$bR['copper']."</td><td>".$bR['t_time']."</td></tr>";
		}
	
		echo "		</table>";
	}

	function gjaHistory(){
		echo "		<table class='table table-bordered'>";
		echo "			<tr class='info'><td>User</td><td>Item Name</td><td>Value</td><td>Quantity</td><td>Location</td><td>Time</td></tr>";
		$q1 = "SELECT * FROM `gja_log` WHERE vaultID = '".$GLOBALS['vault']."'";
		$r1 = $GLOBALS['mysqli']->query($q1);

		while ($bR = $r1->fetch_assoc()) {
			$i++;
			$q3 = "SELECT bagName FROM `bags` WHERE bagID = '".$bR['gjaLoc']."'";
			$r3 = $GLOBALS['mysqli']->query($q3);
			$bag = $r3->fetch_assoc();
			$q2 = "SELECT display_name FROM `tpwp_users` WHERE ID = '".$bR['changeBy']."'";
			$r2 = $GLOBALS['mysqli']->query($q2);
			$user = $r2->fetch_assoc();
				if($bR['history'] == 1){
					$class = "danger";
				} else {
					$class = "";
				}
			echo"	<tr class='".$class."'><td>".$user['display_name']."</td><td>".$bR['gjaName']."</td>
					<td>".$bR['gjaValue']."</td><td>".$bR['gjaQty']."</td><td>".$bag['bagName
					']."</td><td>".$bR['gjaTime']."</td></tr>";
		}
		echo "		</table>";
	}

	function itemHistory(){
		echo "		<table class='table table-bordered'>";
		echo "		<tr class='info'><td>User</td><td>Item Name</td><td>Value</td><td>Quantity</td><td>Location</td><td>Time</td></tr>";

		$q1 = "SELECT * FROM `treasure_log` WHERE vaultID = '".$GLOBALS['vault']."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		while ($bR = $r1->fetch_assoc()) {
			$i++;
			$q3 = "SELECT bagName FROM `bags` WHERE bagID = '".$bR['tLoc']."'";
			$r3 = $GLOBALS['mysqli']->query($q3);
			$bag = $r3->fetch_assoc();
			$q2 = "SELECT display_name FROM `tpwp_users` WHERE ID = '".$bR['changeBy']."'";
			$r2 = $GLOBALS['mysqli']->query($q2);
			$user = $r2->fetch_assoc();
				if($bR['history'] == 1){
					$class = "danger";
				} else {
					$class = "";
				}
			echo"	<tr class='".$class."'><td>".$user['display_name']."</td><td>".$bR['tName']."</td>
					<td>".$bR['tValue']."</td><td>".$bR['tQty']."</td><td>".$bag['bagName']."</td><td>".$bR['tTime']."</td></tr>";
		}

		echo "		</table>";
	}

?>