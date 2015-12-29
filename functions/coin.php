<?php
/// Function to refresh the party coin window ////
	function getCoin(){
		if($GLOBALS['basCurID'] == 0){
			$q1 = "SELECT * FROM `vaultCurrency` WHERE vaultID = '0' ORDER BY multiplier DESC";
		} else {
			$q1 = "SELECT * FROM `vaultCurrency` WHERE vaultID = '".$GLOBALS['vault']."' ORDER BY multiplier DESC";
		}
		$result = $GLOBALS['mysqli']->query($q1);
		while($row = $result->fetch_assoc()){
			$q2 = "SELECT SUM(value) as val FROM `coinNew` WHERE vcID = '".$row['vcID']."' AND history = '0' AND vaultID = '".$GLOBALS['vault']."'";	
			$r2 = $GLOBALS['mysqli']->query($q2);
			$res = $r2->fetch_assoc();
			echo "<li class='list-group-item'><b>".$row['currencyName'].": </b><span class='badge'>".floatval($res['val'])."</span></li>";
		}
	}


	function coinAddBlock(){
		if($GLOBALS['basCurID'] == 0){
			$q1 = "SELECT * FROM `vaultCurrency` WHERE vaultID = '0' ORDER BY multiplier DESC";
		} else {
			$q1 = "SELECT * FROM `vaultCurrency` WHERE vaultID = '".$GLOBALS['vault']."' ORDER BY multiplier DESC";
		}
		$result = $GLOBALS['mysqli']->query($q1);
		while($row = $result->fetch_assoc()){
			echo "
				<div class='row'>
					<div class='col-xs-12'>
						<div class='input-group'>
							<input type='text' class='form-control' id='".$row['vcID']."' name='".$row['vcID']."' placeholder='".$row['currencyName']."'>
							<span class='input-group-btn'>
								<button class='btn btn-success addCurrency' value='".$row['vcID']."' type='button'>Add</button>
							</span>
						</div>
					</div>
				</div>";
		}
	}

	function addCurrencyValue($cur){
		$q1 = "INSERT INTO `coinNew` (vaultID,vcID,value,changeBy) VALUES (".$GLOBALS['vault'].",?,?,'".$GLOBALS['user']."')";
		$stmt = $GLOBALS['mysqli']->prepare($q1);
		$stmt->bind_param('ii',$cur[0].$cur[1]);
		$stmt->execute();

	}


	function newSplit($val){
		$party = $val;
		$cTotal = 0;
		$coins = array();
		$q1 = "SELECT * FROM `vaultCurrency` WHERE vaultID = '".$GLOBALS['vault']."' ORDER BY multiplier DESC";
		$result = $GLOBALS['mysqli']->query($q1);
		while($row = $result->fetch_assoc()){
			$q2 = "SELECT SUM(value) as val FROM `coinNew` WHERE vcID = '".$row['vcID']."' AND history = '0' AND vaultID = '".$GLOBALS['vault']."'";	
			$r2 = $GLOBALS['mysqli']->query($q2);
			$res = $r2->fetch_assoc();
			$newVal = $res['val']*$row['multiplier'];
			$cTotal += $newVal;
		}
		$ptySpt = $party+$GLOBALS['tInc']; // number of party to split between
		$allSplit = $cTotal/$ptySpt; // Split copper between the members of the party.
		$mod = $cTotal%$ptySpt;

		$q1 = "SELECT * FROM `vaultCurrency` WHERE vaultID = '".$GLOBALS['vault']."' ORDER BY multiplier DESC";
		$result = $GLOBALS['mysqli']->query($q1);
		while($row = $result->fetch_assoc()){
			$i++;
			$coins[$i]["name"] = $row['currencyName'];
			$coins[$i]["id"] = $row['vcID'];
			$coins[$i]["value"] = floor($allSplit/$row['multiplier']);
			if($row['multiplier'] != 1){
				$allSplit = floor($allSplit%$row['multiplier']);
			}
		}
		echo "<h3>Each Party Member Receives:</h3>";
		echo "<ul class='list-group'>";
		foreach($coins as $coin) {
			foreach($coin as $key => $val) {
				if($key == "name") {
					echo "<li class='list-group-item'><b>".$val.": </b><span class='badge'>";
				} elseif($key == "value") {
					echo $val."</span></li>";
				}
			}
		}

		echo "</ul>";

		foreach($coins as &$value){
			if($value['id'] === $GLOBALS['basCurID']){
				$value['value'] = $mod;
				break; // Stop the loop after we've found the item
			}
		}

		if($GLOBALS['tInc'] == 1){
			echo "<h3>The Party Treasury Receives:</h3>";
			echo "<ul class='list-group'>";
			foreach($coins as $coin) {
				foreach($coin as $key => $val) {
					if($key == "name") {
						echo "<li class='list-group-item'><b>".$val.": </b><span class='badge'>";
					} elseif($key == "value") {
						echo $val."</span></li>";
					}
				}
			}
			echo "</ul>";
		}
		$q1 = "UPDATE `coinNew` SET history = '1' WHERE vaultID = '".$GLOBALS['vault']."'";
		$result = $GLOBALS['mysqli']->query($q1);

		if($GLOBALS['tInc'] == 1){
			foreach($coins as $coin) {
				$query = "INSERT INTO `coinNew` (vcID,vaultID,value,changeBy) VALUES ('".$coin['id']."','".$GLOBALS['vault']."','".$coin['value']."','".$GLOBALS['user']."')";
				$result = $GLOBALS['mysqli']->query($query);
			}
		} else {
			$val = $allSplit + $mod;
			$q2 = "INSERT INTO `coinNew` (vcID, value, changeBy, vaultID) VALUES ('".$GLOBALS['basCurID']."','".$val."','".$GLOBALS['user']."','".$GLOBALS['vault']."')";
			$result = $GLOBALS['mysqli']->query($q2);
		}
	}
?>