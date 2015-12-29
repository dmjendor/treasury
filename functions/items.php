<?php
	function getItems(){
		$q2 = "SELECT * FROM `bags` WHERE vaultID = '".$GLOBALS['vault']."'";
		$r2 = $GLOBALS['mysqli']->query($q2);
		while ($bResult = $r2->fetch_assoc()) {
			echo "<h4>".$bResult['bagName']."</h4>";
			echo "<ul class='list-group treasure-list' data-id='".$bResult['bagID']."'>";
			$q1 = "SELECT * FROM `treasure` WHERE `tLoc` = '".$bResult['bagID']."' AND vaultID = '".$GLOBALS['vault']."'";
			$r1 = $GLOBALS['mysqli']->query($q1);
			while($tResult = $r1->fetch_assoc()){
				$i++;
				$val = $tResult['tValue']/$GLOBALS['curMult'];
				if($tResult['tLoc'] == $bResult['bagID']){
					echo "<li class='list-group-item itemGridItem' data-id='".$tResult['treasureID']."'>";
					if($GLOBALS['itemPerm'] == TRUE){
					echo "   <button type='button' class='increaseItem btn btn-success btn-xs' title='Add One to Treasury' value='".$tResult['treasureID']."'>
								<span class='glyphicon glyphicon-plus'></span>
							</button>&nbsp;
							<button type='button' class='decreaseItem btn btn-danger btn-xs' id='".$i."d' title='Remove One from Treasury' value='".$tResult['treasureID']."'>
								<span class='glyphicon glyphicon-minus'></span>
							</button>&nbsp;
							<button type='button' class='buyItem btn btn-success btn-xs' title='Buy One for Treasury' value='".$tResult['treasureID']."'>
								<span class='glyphicon glyphicon-usd'></span>
							</button>&nbsp;
							<button type='button' class='sellItem btn btn-danger btn-xs' id='".$i."s' title='Sell One to Treasury' value='".$tResult['treasureID']."'>
								<span class='glyphicon glyphicon-usd'></span>
							</button>&nbsp;";
					}
					echo $tResult['tName']." <span class='text-success small'>(".$val.$GLOBALS['curAbrev'].")</span><span class='badge'>".$tResult['tQty']."</span></li>";
				}
			}
			echo "</ul>";
		}
	}

	function addItem($item){
		if($item[4] == 1){
			if($GLOBALS['curMult']!=0){
				$baseVal = $item[1]*$GLOBALS['curMult'];
			} else {
				$baseVal = $item[1];
			}

			if($GLOBALS['ibMarkup']!=0){
				$markup = $GLOBALS['ibMarkup']/100;
				$mVal = $baseVal*$markup;
				$iVal = $baseVal+$mVal;
			} else {
				$iVal = $baseVal;
			}
		} else {
			$iVal = $item[1]*$GLOBALS['curMult'];
		}

		$q1 = "INSERT INTO `treasure` (`tName`,`tValue`,`tQty`,`tLoc`,`changeBy`, `vaultID`) VALUES ( ?, ?, ?, ? ,'".$GLOBALS['user']."', '".$GLOBALS['vault']."')";
		$stmt = $GLOBALS['mysqli']->prepare($q1);
		$stmt->bind_param('siii',$item[0],$iVal,$item[2],$item[3]);
		$stmt->execute();

		$q2 = "INSERT INTO `treasure_log` (`tName`,`tValue`,`tQty`,`tLoc`,`changeBy`, `vaultID`) VALUES ( ?, ?, ?, ?,'".$GLOBALS['user']."', '".$GLOBALS['vault']."')";
		$stmt = $GLOBALS['mysqli']->prepare($q2);
		$stmt->bind_param('sdii',$item[0],$iVal,$item[2],$item[3]);
		$stmt->execute();

		if($item[4] == 1){
			$insertVal = $iVal*-1;
			$q1 = "INSERT INTO `coinNew` (`vaultID`,`vcID`,`value`,`changeBy`) VALUES ( '".$GLOBALS['vault']."','".$GLOBALS['comCurID']."' ,? ,'".$GLOBALS['user']."')";
			$stmt = $GLOBALS['mysqli']->prepare($q1);
			$stmt->bind_param('i',$insertVal);
			$stmt->execute();
		}

		getItems();
	}

	function moveItem($item){
		$q1 = "UPDATE `treasure` SET `tLoc` = '".$item[0]."', changeBy = '".$GLOBALS['user']."' WHERE `treasureID` = '".$item[1]."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		getItems();
	}

	function increaseItem($id){
		$q1 = "CALL increaseItem(".$id.",'".$GLOBALS['user']."','".$GLOBALS['vault']."')";
		if($r1 = $GLOBALS['mysqli']->query($q1)){
			$f1 = $r1->fetch_assoc();
			echo $f1['@qty := tQty'];
		}
	}

	function decreaseItem($id){
		$q1 = "CALL decreaseItem(".$id.",'".$GLOBALS['user']."','".$GLOBALS['vault']."')";
		if($r1 = $GLOBALS['mysqli']->query($q1)){
			$f1 = $r1->fetch_assoc();
			echo $f1['@qty := tQty'];
		}
	}

	function sellItem($id){
		$mv = $val = $qty = 0;

		$q1 = "SELECT * FROM `treasure` WHERE treasureId = '".$id."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		while ($d1 = $r1->fetch_assoc()) {
			$q2 = "UPDATE `treasure` SET tQty = tQty - 1, changeBy = '".$GLOBALS['user']."' WHERE treasureId = '".$id."'";
			$r2 = $GLOBALS['mysqli']->query($q2);
			$qty = $d1['tQty'] - 1;

			if($GLOBALS['comCurID']!=0){
				$val = $d1['tValue'] / $GLOBALS['curMult'];
			} else {
				$val = $d1['tValue'];
			}

			if($GLOBALS['isMarkup'] != 0){
				$mv = $val * ($GLOBALS['isMarkup']/-100) ;
				$val = $val - $mv ;
			}
			$q4 = "INSERT INTO `coinNew` (vaultID,vcID,value,changeBy) VALUES ('".$GLOBALS['vault']."', '".$GLOBALS['comCurID']."',".$val.",'".$GLOBALS['user']."' )";
			$r4 = $GLOBALS['mysqli']->query($q4);

			if($qty == 0) {
				$q5 = "DELETE FROM `treasure` WHERE treasureId = '".$id."'";
				$r5 = $GLOBALS['mysqli']->query($q5);
			}
			$q6 = "INSERT INTO `treasure_log` (vaultID,tName, tValue, tLoc, tQty, changeBy) VALUES ('".$GLOBALS['vault']."',".$d1['tName'].",".$val.",".$d1['tLoc'].",".$qty.",'".$GLOBALS['user']."')";
			$r6 = $GLOBALS['mysqli']->query($q6);
		}

		echo $qty;
	}

	function buyItem($id){
		$mv = $val = $qty = 0;

		$q1 = "SELECT * FROM `treasure` WHERE treasureId = '".$id."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		while ($d1 = $r1->fetch_assoc()) {
			$q2 = "UPDATE `treasure` SET tQty = tQty + 1, changeBy = '".$GLOBALS['user']."' WHERE treasureId = '".$id."'";
			$r2 = $GLOBALS['mysqli']->query($q2);
			$qty = $d1['tQty'] + 1;

			if($GLOBALS['comCurID']!=0){
				$val = $d1['tValue'] / $GLOBALS['curMult'];
			} else {
				$val = $d1['tValue'];
			}

			if($GLOBALS['ibMarkup'] != 0){
				$mv = $val * ($GLOBALS['ibMarkup']/100) ;
				$val = $val - $mv ;
			}
			$q4 = "INSERT INTO `coinNew` (vaultID,vcID,value,changeBy) VALUES ('".$GLOBALS['vault']."', '".$GLOBALS['comCurID']."',".$val.",'".$GLOBALS['user']."' )";
			$r4 = $GLOBALS['mysqli']->query($q4);

			$q6 = "INSERT INTO `treasure_log` (vaultID,tName, tValue, tLoc, tQty, changeBy) VALUES ('".$GLOBALS['vault']."',".$d1['tName'].",".$val.",".$d1['tLoc'].",".$qty.",'".$GLOBALS['user']."')";
			$r6 = $GLOBALS['mysqli']->query($q6);
		}

		echo $qty;
	}

	function quickItem($item){
		$q1 = "SELECT * FROM `itemList` WHERE itemId = '".$item[0]."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		$b1 = $r1->fetch_assoc();
		$itemVal = $b1['itemValue'];
		$itemName = $b1['itemName'];
		$itemLoc = $item[2];
	
		for($i = 0; $i < count($item[1]); $i++){
			$q2 = "SELECT * FROM `modifiers` WHERE modId = '".$item[1][$i]."'";
			$r2 = $GLOBALS['mysqli']->query($q2);
			$b2 = $r2->fetch_assoc();
			$itemVal += $b2['value'];
			$itemName .= ", ".$b2['name'];
		}

		if($GLOBALS['ibMarkup']!=0){
			$markup = $GLOBALS['ibMarkup']/100;
			$mVal = $itemVal*$markup;
			$iVal = $itemVal+$mVal;
		} else {
			$iVal = $itemVal;
		}
	

		$q3 = "INSERT INTO `treasure` (`tName`,`tValue`,`tQty`,`tLoc`,`changeBy`, `vaultID`) values ('".$itemName."','".$itemVal."','1','".$itemLoc."','".$GLOBALS['user']."', '".$GLOBALS['vault']."')";
		$r3 = $GLOBALS['mysqli']->query($q3);

		$q4 = "INSERT INTO `treasure_log` (`tName`,`tValue`,`tQty`,`tLoc`,`changeBy`, `vaultID`) values ('".$itemName."','".$itemVal."','1','".$itemLoc."','".$GLOBALS['user']."', '".$GLOBALS['vault']."')";
		$r4 = $GLOBALS['mysqli']->query($q4);

		if($item[4] == 1){
			$insertVal = $iVal*-1;
			$q1 = "INSERT INTO `coinNew` (`vaultID`,`vcID`,`value`,`changeBy`) VALUES ( '".$GLOBALS['vault']."','".$GLOBALS['comCurID']."' ? ,'".$GLOBALS['user']."')";
			$stmt = $GLOBALS['mysqli']->prepare($q1);
			$stmt->bind_param('i',$insertVal);
			$stmt->execute();
		}		

		getItems();
	}


	function editItems($id){
		$q1 = "SELECT tName, tQty, tValue, tLoc FROM `treasure` WHERE treasureID = ?";
		$stmt = $GLOBALS['mysqli']->prepare($q1);
		$stmt->bind_param('i',$id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($name,$qty,$value,$loc);
		while ($stmt->fetch()) {
			echo "
				<div class='row lead' data-id='".$id."'>
					<div class='col-xs-4'>
						<span>Item Name</span>
					</div>
					<div class='col-xs-8'>
						<input class='form-control' name='newTName' id='newTName' value='".$name."' />
					</div>

				</div>
				<div class='row lead'>
					<div class='col-xs-4'>
						<span>Item Quantity</span>
					</div>
					<div class='col-xs-8'>
						<input class='form-control' name='newTQty' id='newTQty' value='".$qty."' />
					</div>

				</div>
				<div class='row lead'>
					<div class='col-xs-4'>
						<span>Item Value</span>
					</div>
					<div class='col-xs-8'>
						<input class='form-control' name='newTValue' id='newTValue' value='".$value."' />
					</div>

				</div>
				<div class='row lead'>
					<div class='col-xs-4'>
						<span>Location</span>
					</div>
					<div class='col-xs-8'><select id='newTLoc' class='form-control'>";
					$q2 = "SELECT * FROM `bags` WHERE vaultID = '".$GLOBALS['vault']."'";
					$r2 = $GLOBALS['mysqli']->query($q2);
					while ($d1 = $r2->fetch_assoc()) {
						if($d1['bagID'] == $loc){ $selected = "selected='selected'"; } else { $selected = ""; }
						echo "<option value='".$d1['bagID']."' ".$selected.">".$d1['bagName']."</option>";
					}
			echo "</select>
				</div>

				</div>
				<div class='row'>
					<div class='col-xs-12'>
						<button class='form-control btn btn-success' id='saveItem' value='".$id."'>Save Item</button>
					</div>
				</div>";
		
		}

	   /* free results */
	   $stmt->free_result();

	   /* close statement */
	   $stmt->close();
	}

	function saveItem($item){
		$q1 = "UPDATE `treasure` SET tName = ?, tQty = ?, tValue = ?, tLoc = ?, changeBy = '".$GLOBALS['user']."' WHERE treasureID = '".$item[0]."'";
		$stmt = $GLOBALS['mysqli']->prepare($q1);
		$stmt->bind_param('siii',$item[1],$item[2],$item[3],$item[4]);
		$stmt->execute();
	}
?>