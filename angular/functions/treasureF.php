<?php
	global $mysqli;
	define("DB_HOST","mysql.partytreasury.com");
	define("DB_USER","ptdbdnd");
	define("DB_PASSWORD", "Z9jC7G_Xtj9S!9G");
	define("DB_DATABASE", "dndptdb");

	//$GLOBALS['mysqli'] = new mysqli("mysql.draconys.com", "dmjendor", "Gr8wolfs", "local_dnd");
	$GLOBALS['mysqli'] = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

/// Function to refresh the party coin window ////
	function partyCoin(){
		$query = "SELECT SUM(platinum) AS pt, SUM(gold) AS au, SUM(silver) AS ag, SUM(copper) AS cu FROM `coin`";
		$result = $GLOBALS['mysqli']->query($query);
		$row = $result->fetch_assoc();
		
		echo "<li class='list-group-item'><b>Platinum: </b><span class='badge'>".$row['pt']."</span></li>";
		echo "<li class='list-group-item'><b>Gold: </b><span class='badge'>".$row['au']."</span></li>";
		echo "<li class='list-group-item'><b>Silver: </b><span class='badge'>".$row['ag']."</span></li>";
		echo "<li class='list-group-item'><b>Copper: </b><span class='badge'>".$row['cu']."</span></li>";
	}

/// Query and return the amount of platinum in the treasury ///
	function getPt(){
		$query = "SELECT platinum FROM `coin` WHERE `platinum` != 0";
		$result = $GLOBALS['mysqli']->query($query);
		while ($row = $result->fetch_assoc()) {
			$pTot += $row['platinum'];
		}
		echo $pTot;
	}

/// Query and return the amount of gold in the treasury ///
	function getAu(){
		$query = "SELECT gold FROM `coin` WHERE `gold` != 0";
		$result = $GLOBALS['mysqli']->query($query);
		while ($row = $result->fetch_assoc()) {
			$gTot += $row['gold'];
		}
		echo $gTot;
	}
/// Query and return the amount of silver in the treasury ///
	function getAg(){
		$query = "SELECT silver FROM `coin` WHERE `silver` != 0";
		$result = $GLOBALS['mysqli']->query($query);
		while ($row = $result->fetch_assoc()) {
			$sTot += $row['silver'];
		}
		echo $sTot;
	}

/// Query and return the amount of copper in the treasury ///
	function getCu(){
		$query = "SELECT copper FROM `coin` WHERE `copper` != 0";
		$result = $GLOBALS['mysqli']->query($query);
		while ($row = $result->fetch_assoc()) {
			$cTot += $row['copper'];
		}
		echo $cTot;
	}
/// Insert a new amount of platinum in the treasury ///
	function setPt($val){
		$query = "INSERT INTO coin ( platinum ) VALUE (".$val.")";
		$result = $GLOBALS['mysqli']->query($query);
	}
/// Insert a new amount of gold in the treasury ///
	function setAu($val){
		$query = "INSERT INTO coin ( gold ) VALUE (".$val.")";
		$result = $GLOBALS['mysqli']->query($query);
	}
/// Insert a new amount of silver in the treasury ///
	function setAg($val){
		$query = "INSERT INTO coin ( silver ) VALUE (".$val.")";
		$result = $GLOBALS['mysqli']->query($query);
	}
/// Insert a new amount of copper in the treasury ///
	function setCu($val){
		$query = "INSERT INTO coin ( copper ) VALUE (".$val.")";
		$result = $GLOBALS['mysqli']->query($query);
	}

	function tSplit($val){
		$query = "SELECT sum(platinum) AS pt, sum(gold) AS au, sum(silver) AS ag, sum(copper) AS cu FROM `coin`";
		$result = $GLOBALS['mysqli']->query($query);
		$coins = array();
		$coins = $result->fetch_assoc(); 
		$cTotal += ( $coins['pt'] * 1000); // Convert platinum to copper
		$cTotal += ( $coins['au'] * 100); // Convert gold to copper
		$cTotal += ( $coins['ag'] * 10); // Convert gold to copper
		$cTotal += $coins['cu']; // Add Copper
		$ptySpt = $val+$GLOBALS['tInc']; // number of party to split between
		$allSplit = $cTotal/$ptySpt; // Split copper between the members of the party.
		$pSplit = floor($allSplit/1000); // Convert copper back to platinum;
		$pMod = floor($allSplit%1000); // Remainder unable to be made into plat
		$gSplit = floor($pMod/100); // Convert copper back into gold;
		$gMod = floor($pMod%100);  // remainder unable to be made into gold
		$sSplit = floor($gMod/10); // convert silver back into gold;
		$cSplit = floor($gMod%10); // remaining copper 

		$q1 = "TRUNCATE TABLE `coin`";
		$result = $GLOBALS['mysqli']->query($q1);

		if($GLOBALS['tInc'] == 1){
			$q2 = "INSERT INTO `coin` (platinum,gold,silver,copper) VALUES ($pSplit,$gSplit,$sSplit,$cSplit)";
			$result = $GLOBALS['mysqli']->query($q2);
			$copper = $cSplit;
		} else {
			$copper = $allSplit%$ptySpt;
			$q2 = "INSERT INTO `coin` (copper) VALUES ($copper)";
			$result = $GLOBALS['mysqli']->query($q2);
		}
		echo "<h3>Each Party Member Receives:</h3>";
		echo "<ul class='list-group'>";
		if(!empty($pSplit)){ echo "<li class='list-group-item'>Platinum: ".$pSplit."</li>"; }
		if(!empty($gSplit)){ echo "<li class='list-group-item'>Gold: ".$gSplit."</li>"; }
		if(!empty($sSplit)){ echo "<li class='list-group-item'>Silver: ".$sSplit."</li>"; }
		if(!empty($cSplit)){ echo "<li class='list-group-item'>Copper: ".$cSplit."</li>"; }
		echo "</ul>";
		echo "<h3>The Party Treasury Receives:</h3>";
		echo "<ul class='list-group'>";
		if(!empty($pSplit)){ echo "<li class='list-group-item'>Platinum: ".$pSplit."</li>"; }
		if(!empty($gSplit)){ echo "<li class='list-group-item'>Gold: ".$gSplit."</li>"; }
		if(!empty($sSplit)){ echo "<li class='list-group-item'>Silver: ".$sSplit."</li>"; }
		if(!empty($copper)){ echo "<li class='list-group-item'>Copper: ".$copper."</li>"; }
		echo "</ul>";

	}

	function getItems(){
		$q2 = "SELECT * FROM `bags`";
		$r2 = $GLOBALS['mysqli']->query($q2);
		while ($bResult = $r2->fetch_assoc()) {
			echo "<h4>".$bResult['bagName']."</h4>";
			echo "<ul class='list-group treasure-list' data-id='".$bResult['bagId']."'>";
			$q1 = "SELECT * FROM `treasure` WHERE `tLoc` = ".$bResult['bagId'];
			$r1 = $GLOBALS['mysqli']->query($q1);
			while($tResult = $r1->fetch_assoc()){
				$i++;
				if($tResult['tLoc'] == $bResult['bagId']){
					echo "<li class='list-group-item' data-id='".$tResult['treasureId']."'>
							<button type='button' class='increaseItem btn btn-success btn-xs' title='Add One to Treasury' value='".$tResult['treasureId']."'>
								<span class='glyphicon glyphicon-plus'></span>
							</button>&nbsp;
							<button type='button' class='decreaseItem btn btn-danger btn-xs' id='".$i."d' title='Remove One from Treasury' value='".$tResult['treasureId']."'>
								<span class='glyphicon glyphicon-minus'></span>
							</button>&nbsp;
							<button type='button' class='buyItem btn btn-success btn-xs' title='Buy One for Treasury' value='".$tResult['treasureId']."'>
								<span class='glyphicon glyphicon-usd'></span>
							</button>&nbsp;
							<button type='button' class='sellItem btn btn-danger btn-xs' id='".$i."s' title='Sell One to Treasury' value='".$tResult['treasureId']."'>
								<span class='glyphicon glyphicon-usd'></span>
							</button>&nbsp;
							".$tResult['tName']." <span class='value'>(".$tResult['tValue']."gp)</span><span class='badge'>".$tResult['tQty']."</span></li>";
				}
			}
			echo "</ul>";
		}
	}

	function addItem($item){
		$q1 = "INSERT INTO `treasure` (`tName`,`tValue`,`tQty`,`tLoc`) VALUES ('".$item[0]."','".$item[1]."','".$item[2]."','".$item[3]."')";
		$r1 = $GLOBALS['mysqli']->query($q1);
		getItems();
	}

	function moveItem($item){
		$q1 = "UPDATE `treasure` SET `tLoc` = '".$item[0]."' WHERE `treasureId` = '".$item[1]."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		getItems();
	}

	function increaseItem($id){
		$q1 = "CALL increaseItem(".$id.")";
		$r1 = $GLOBALS['mysqli']->query($q1);
		$f1 = $r1->fetch_assoc();
		echo $f1['new_qty'];
	}

	function decreaseItem($id){
		$q1 = "CALL decreaseItem(".$id.")";
		$r1 = $GLOBALS['mysqli']->query($q1);
		$f1 = $r1->fetch_assoc();
		echo $f1['new_qty'];
	}

	function sellItem($id){
		$q1 = "CALL sellItem(".$id.")";
		$r1 = $GLOBALS['mysqli']->query($q1);
		$f1 = $r1->fetch_assoc();
		echo $f1['new_qty'];
	}

	function buyItem($id){
		$q1 = "CALL buyItem(".$id.")";
		$r1 = $GLOBALS['mysqli']->query($q1);
		$f1 = $r1->fetch_assoc();
		echo $f1['new_qty'];
	}

	function quickItem($item){
		$q1 = "SELECT * FROM `itemList` WHERE itemId = '".$item[0]."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		$b1 = $r1->fetch_assoc();
		$itemVal = $b1['itemValue'];
		$itemName = $b1['itemName'];
		$itemLoc = $item[2];

		for($i = 0; $i < count($item[2]); $i++){
			$q2 = "SELECT * FROM `modifiers` WHERE modId = '".$item[1][$i]."'";
			$r2 = $GLOBALS['mysqli']->query($q2);
			$b2 = $r2->fetch_assoc();
			$itemVal += $b2['value'];
			$itemName .= ", ".$b2['name'];
		}

		$q3 = "INSERT INTO `treasure` (`tName`,`tValue`,`tQty`,`tLoc`) values ('".$itemName."','".$itemVal."','1','".$itemLoc."')";
		$r3 = $GLOBALS['mysqli']->query($q3);
		getItems();
	}
	

	function addGJA($item){
		$q1 = "INSERT INTO `gemsjewelryart` (`gjaName`,`gjaValue`,`gjaQty`,`gjaLoc`) VALUES ('".$item[0]."','".$item[1]."','".$item[2]."','".$item[3]."')";
		$r1 = $GLOBALS['mysqli']->query($q1);
		getGJA();
	}

	function quickGJA($item){
		$q1 = "SELECT * FROM `gjaList` WHERE itemId = '".$item[0]."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		$b1 = $r1->fetch_assoc();
//		print_r($b1);
		
		$q2 = "SELECT * FROM `gja_tree` WHERE id = '".$item[1]."'";
		$r2 = $GLOBALS['mysqli']->query($q2);
		$b2 = $r2->fetch_assoc();
				
		$itemVal = floor(rand($b1['itemLVal'],$b1['itemHVal']));
		if($b2['id'] >200 AND $b2['id'] < 300){
			$itemName = $b2['name'].", ".$b1['itemName'];
		} else {
			$itemName = $b1['itemName'];
		}
		$itemLoc = $item[2];

		$q3 = "INSERT INTO `gemsjewelryart` (`gjaName`,`gjaValue`,`gjaLoc`,`gjaQty`) values ('".$itemName."','".$itemVal."','".$itemLoc."','1')";
		$r3 = $GLOBALS['mysqli']->query($q3);
		getGJA();
	}

	function getGJA(){
		$q2 = "SELECT * FROM `bags`";
		$r2 = $GLOBALS['mysqli']->query($q2);
		while ($bResult = $r2->fetch_assoc()) {
			echo "<h4>".$bResult['bagName']."</h4>";
			echo "<ul class='list-group'>";
			$q1 = "SELECT * FROM `gemsjewelryart` WHERE `gjaLoc` = ".$bResult['bagId'];
			$r1 = $GLOBALS['mysqli']->query($q1);
			while($tResult = $r1->fetch_assoc()){
				$i++;
				if($tResult['gjaLoc'] == $bResult['bagId']){
					echo "<li class='list-group-item'>
							<button type='button' class='increaseGJA btn btn-success btn-xs' title='Add One to Treasury' value='".$tResult['gjaId']."'>
								<span class='glyphicon glyphicon-plus'></span>
							</button>&nbsp;
							<button type='button' class='decreaseGJA btn btn-danger btn-xs' id='".$i."d' title='Remove One from Treasury' value='".$tResult['gjaId']."'>
								<span class='glyphicon glyphicon-minus'></span>
							</button>&nbsp;
							<button type='button' class='buyGJA btn btn-success btn-xs' title='Buy One for Treasury' value='".$tResult['gjaId']."'>
								<span class='glyphicon glyphicon-usd'></span>
							</button>&nbsp;
							<button type='button' class='sellGJA btn btn-danger btn-xs' id='".$i."s' title='Sell One to Treasury' value='".$tResult['gjaId']."'>
								<span class='glyphicon glyphicon-usd'></span>
							</button>&nbsp;
							".$tResult['gjaName']." <span class='value'>(".$tResult['gjaValue']."gp)</span><span class='badge'>".$tResult['gjaQty']."</span></li>";
				}
			}

		}
	}

	function increaseGJA($id){
		$q1 = "CALL increaseGJA(".$id.")";
		$r1 = $GLOBALS['mysqli']->query($q1);
		$f1 = $r1->fetch_assoc();
		echo $f1['new_qty'];
	}

	function decreaseGJA($id){
		$q1 = "CALL decreaseGJA(".$id.")";
		$r1 = $GLOBALS['mysqli']->query($q1);
		$f1 = $r1->fetch_assoc();
		echo $f1['new_qty'];
	}

	function sellGJA($id){
		$q1 = "CALL sellGJA(".$id.")";
		$r1 = $GLOBALS['mysqli']->query($q1);
		$f1 = $r1->fetch_assoc();
		echo $f1['new_qty'];
	}

	function buyGJA($id){
		$q1 = "CALL buyGJA(".$id.")";
		$r1 = $GLOBALS['mysqli']->query($q1);
		$f1 = $r1->fetch_assoc();
		echo $f1['new_qty'];
	}

	function bagMan(){
		$q1 = "SELECT * FROM `bags`";
		$r1 = $GLOBALS['mysqli']->query($q1);
		echo "
		<div class='col-xs-6'>
			<div class='panel'>
				<ul class='list-group bagList'>";
				while ($bResult = $r1->fetch_assoc()) {
					$i++;
					echo "<li class='list-group-item'>
							".$bResult['bagName']."
							<button type='button' class='pull-right removeBag btn btn-danger btn-xs' id='".$i."d' title='Remove Bag' value='".$bResult['bagId']."'>
								<span class='glyphicon glyphicon-minus'></span>
							</button></li>";
				}
		echo "
				</ul>
			</div>
		</div>
		<div class='col-xs-6'>
			<div class='form-group'>
				<div class='input-group'>
					<input type='text' class='form-control' id='bagName' placeholder='Bag Name'>
					<span class='input-group-btn'>
						<button class='btn btn-success addBag' type='button'>Add</button>
					</span>
				</div>
			</div>
			<div class='form-group'>
				<div class='input-group'>
					<select class='form-control' id='quickBag'>
						<option selected='selected' value='0'>--</option>";
			$q8 = "SELECT * FROM `bagList` ORDER BY bagValue";
			$r8 = $GLOBALS['mysqli']->query($q8);
			while($bResult = $r8->fetch_assoc()){
				echo "<option value='".$bResult['bagId']."'>".$bResult['bagName']."</option>";
			}
		echo "
					</select>
					<span class='input-group-btn'>
						<button class='btn btn-success quickBag' type='button'>Add</button>
					</span>
				</div>
			</div>
		</div>";
	}
	
	function quickBag($bag){
		$q1 = "SELECT * FROM `bagList` WHERE bagId = ".$bag;
		$r1 = $GLOBALS['mysqli']->query($q1);
		$bR = $r1->fetch_assoc();

		$q2 = "INSERT INTO `bags` (`bagId`,`bagName`) VALUES (NULL,'".$bR['bagName']."')";
		$r2 = $GLOBALS['mysqli']->query($q2);
		getBags();
	}

	function removeBag($bag){
		$q2 = "DELETE FROM `bags` WHERE `bagId` = ".$bag;
		$r2 = $GLOBALS['mysqli']->query($q2);
		getBags();
	}

	function getBags(){
		$q1 = "SELECT * FROM `bags`";
		$r1 = $GLOBALS['mysqli']->query($q1);
		while ($bR = $r1->fetch_assoc()) {
			$i++;
			echo "<li class='list-group-item'>
					".$bR['bagName']."
					<button type='button' class='pull-right removeBag btn btn-danger btn-xs' id='".$i."d' title='Remove Bag' value='".$bR['bagId']."'>
						<span class='glyphicon glyphicon-minus'></span>
					</button></li>";
		}
	}


?>