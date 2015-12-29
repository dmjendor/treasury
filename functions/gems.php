<?php
	function addGJA($item){
		if($item[4] == 1){
			if($GLOBALS['curMult']!=0){
				$baseVal = $item[1]*$GLOBALS['curMult'];
			} else {
				$baseVal = $item[1];
			}

			if($GLOBALS['gbMarkup']!=0){
				$markup = $GLOBALS['gbMarkup']/100;
				$mVal = $baseVal*$markup;
				$gVal = $baseVal+$mVal;
			} else {
				$gVal = $baseVal;
			}

		} else {
			$gVal = $item[1]*$GLOBALS['curMult'];
		}
		$q1 = "INSERT INTO `gemsjewelryart` (`gjaName`,`gjaValue`,`gjaQty`,`gjaLoc`,`changeBy`, `vaultID`) VALUES ( ? , ? , ?, ?, '".$GLOBALS['user']."', '".$GLOBALS['vault']."')";
		$stmt = $GLOBALS['mysqli']->prepare($q1);
		$stmt->bind_param('siii',$item[0],$gVal,$item[2],$item[3]);
		$stmt->execute();

		$q2 = "INSERT INTO `gja_log` (`gjaName`,`gjaValue`,`gjaQty`,`gjaLoc`,`changeBy`, `vaultID`) VALUES ( ? , ? , ? , ? ,'".$GLOBALS['user']."', '".$GLOBALS['vault']."')";
		$stmt = $GLOBALS['mysqli']->prepare($q2);
		$stmt->bind_param('ssss',$item[0],$gVal,$item[2],$item[3]);
		$stmt->execute();

		if($item[4] == 1){
			$insertVal = $iVal*-1;
			$q1 = "INSERT INTO `coinNew` (`vaultID`,`vcID`,`value`,`changeBy`) VALUES ( '".$GLOBALS['vault']."','".$GLOBALS['comCurID']."' ? ,'".$GLOBALS['user']."')";
			$stmt = $GLOBALS['mysqli']->prepare($q1);
			$stmt->bind_param('i',$insertVal);
			$stmt->execute();
		}	
		getGJA();
	}

	function quickGJA($item){
		$q1 = "SELECT * FROM `gjaList` WHERE itemId = '".$item[0]."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		$b1 = $r1->fetch_assoc();

		
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

		$q3 = "INSERT INTO `gemsjewelryart` (`gjaName`,`gjaValue`,`gjaLoc`,`gjaQty`,`changeBy`, `vaultID`) values ('".$itemName."','".$itemVal."','".$itemLoc."','1','".$GLOBALS['user']."', '".$GLOBALS['vault']."')";
		$r3 = $GLOBALS['mysqli']->query($q3);

		$q4 = "INSERT INTO `gja_log` (`gjaName`,`gjaValue`,`gjaLoc`,`gjaQty`,`changeBy`, `vaultID`) values ('".$itemName."','".$itemVal."','".$itemLoc."','1','".$GLOBALS['user']."', '".$GLOBALS['vault']."')";
		$r4 = $GLOBALS['mysqli']->query($q4);

		if($item[4] == 1){
			$insertVal = $iVal*-1;
			$q1 = "INSERT INTO `coinNew` (`vaultID`,`vcID`,`value`,`changeBy`) VALUES ( '".$GLOBALS['vault']."','".$GLOBALS['comCurID']."' ? ,'".$GLOBALS['user']."')";
			$stmt = $GLOBALS['mysqli']->prepare($q1);
			$stmt->bind_param('i',$insertVal);
			$stmt->execute();
		}	

		getGJA();
	}

	function getGJA(){
		$q2 = "SELECT * FROM `bags` WHERE vaultID = '".$GLOBALS['vault']."'";
		$r2 = $GLOBALS['mysqli']->query($q2);
		while ($bResult = $r2->fetch_assoc()) {
			echo "<h4>".$bResult['bagName']."</h4>";
			echo "<ul class='list-group gem-list' data-id='".$bResult['bagID']."'>";
			$q1 = "SELECT * FROM `gemsjewelryart` WHERE `gjaLoc` = '".$bResult['bagID']."' AND vaultID = '".$GLOBALS['vault']."'";
			$r1 = $GLOBALS['mysqli']->query($q1);
			while($tResult = $r1->fetch_assoc()){
				$i++;
				$val = $tResult['gjaValue']/$GLOBALS['curMult'];
				if($tResult['gjaLoc'] == $bResult['bagID']){
					echo "<li class='list-group-item gjaGridItem' data-id='".$tResult['gjaId']."'>";
					if($GLOBALS['itemPerm'] == TRUE){
					echo "   <button type='button' class='increaseGJA btn btn-success btn-xs' title='Add One to Treasury' value='".$tResult['gjaId']."'>
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
							</button>&nbsp;";
					}
					echo $tResult['gjaName']." <span class='small text-success'>(".$val."gp)</span><span class='badge'>".$tResult['gjaQty']."</span></li>";
				}
			}

		}
	}
	function moveGJA($item){
		$q1 = "UPDATE `gemsjewelryart` SET `gjaLoc` = '".$item[0]."', changeBy = '".$GLOBALS['user']."' WHERE `gjaId` = '".$item[1]."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		getGJA();
	}

	function increaseGJA($id){
		$q1 = "CALL increaseGJA(".$id.",'".$GLOBALS['user']."', '".$GLOBALS['vault']."')";
		if($r1 = $GLOBALS['mysqli']->query($q1)){
			$f1 = $r1->fetch_assoc();
			echo $f1['@qty := gjaQty'];
		}
	}

	function decreaseGJA($id){
		$q1 = "CALL decreaseGJA(".$id.",'".$GLOBALS['user']."', '".$GLOBALS['vault']."')";
		if($r1 = $GLOBALS['mysqli']->query($q1)){
			$f1 = $r1->fetch_assoc();
			echo $f1['@qty := gjaQty'];
		}
	}

	function sellGJA($id){
		$mv = 0;
		$val = 0;
		$newVal = 0;
		$qty = 0;

		$q1 = "SELECT * FROM `gemsjewelryart` WHERE gjaId = '".$id."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		while ($d1 = $r1->fetch_assoc()) {
			$q2 = "UPDATE `gemsjewelryart` SET gjaQty = gjaQty - 1, changeBy = '".$GLOBALS['user']."' WHERE gjaId = '".$id."'";
			$r2 = $GLOBALS['mysqli']->query($q2);
			$qty = $d1['gjaQty'] - 1;

			if($GLOBALS['comCurID']!=0){
				$val = $d1['gjaValue'] / $GLOBALS['curMult'];
			} else {
				$val = $d1['gjaValue'];
			}

			if($GLOBALS['gsMarkup'] != 0){
				$mv = $val * ($GLOBALS['gsMarkup']*-1) ;
				$val = $val + $mv ;
			}
			$q4 = "INSERT INTO `coinNew` (vaultID,vcID,value,changeBy) VALUES ('".$GLOBALS['vault']."', '".$GLOBALS['comCurID']."',".$val.",'".$GLOBALS['user']."' )";
			$r4 = $GLOBALS['mysqli']->query($q4);

			if($qty == 0) {
				$q5 = "DELETE FROM `gemsjewelryart` WHERE gjaId = '".$id."'";
				$r5 = $GLOBALS['mysqli']->query($q5);

			}
			$q6 = "INSERT INTO `gja_log` (vaultID,gjaName, gjaValue, gjaLoc, gjaQty, changeBy) VALUES ('".$GLOBALS['vault']."',".$d1['gjaName'].",".$val.",".$d1['gjaLoc'].",".$qty.",'".$GLOBALS['user']."')";
			$r6 = $GLOBALS['mysqli']->query($q6);
		}

		echo $qty;
	}

	function buyGJA($id){
		$mv = 0;
		$val = 0;
		$newVal = 0;
		$qty = 0;

		$q1 = "SELECT * FROM `gemsjewelryart` WHERE gjaId = '".$id."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		while ($d1 = $r1->fetch_assoc()) {
			$q2 = "UPDATE `gemsjewelryart` SET gjaQty = gjaQty + 1, changeBy = '".$GLOBALS['user']."' WHERE gjaId = '".$id."'";
			$r2 = $GLOBALS['mysqli']->query($q2);
			$qty = $d1['gjaQty'] + 1;

			if($GLOBALS['comCurID']!=0){
				$val = -$d1['gjaValue'] / $GLOBALS['curMult'];
			} else {
				$val = -$d1['gjaValue'];
			}

			if($GLOBALS['gbMarkup'] != 0){
				$mv = $val * ($GLOBALS['gbMarkup']*-1) ;
				$val = $val + $mv ;
			}
			$q4 = "INSERT INTO `coinNew` (vaultID,vcID,value,changeBy) VALUES ('".$GLOBALS['vault']."', '".$GLOBALS['comCurID']."',".$val.",'".$GLOBALS['user']."' )";
			$r4 = $GLOBALS['mysqli']->query($q4);

			$q6 = "INSERT INTO `gja_log` (vaultID,gjaName, gjaValue, gjaLoc, gjaQty, changeBy) VALUES ('".$GLOBALS['vault']."',".$d1['gjaName'].",".$val.",".$d1['gjaLoc'].",".$qty.",'".$GLOBALS['user']."')";
			$r6 = $GLOBALS['mysqli']->query($q6);
		}

		echo $qty;
	}

	function editGJA($id){
		$q1 = "SELECT gjaName, gjaQty, gjaValue, gjaLoc FROM `gemsjewelryart` WHERE gjaId = ?";
		$stmt = $GLOBALS['mysqli']->prepare($q1);
		$stmt->bind_param('i',$id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($name,$qty,$value,$loc);
		while ($stmt->fetch()) {
			echo "
				<div class='row lead' data-id='".$id."'>
					<div class='col-xs-4'>
						<span>Name</span>
					</div>
					<div class='col-xs-8'>
						<input class='form-control' name='newTName' id='newGJAName' value='".$name."' />
					</div>
				</div>
				<div class='row lead'>
					<div class='col-xs-4'>
						<span>Quantity</span>
					</div>
					<div class='col-xs-8'>
						<input class='form-control' name='newTQty' id='newGJAQty' value='".$qty."' />
					</div>
				</div>
				<div class='row lead'>
					<div class='col-xs-4'>
						<span>Value</span>
					</div>
					<div class='col-xs-8'>
						<input class='form-control' name='newTValue' id='newGJAValue' value='".$value."' />
					</div>
				</div>
				<div class='row lead'>
					<div class='col-xs-4'>
						<span>Location</span>
					</div>
					<div class='col-xs-8'><select id='newGJALoc' class='form-control'>";
					$q2 = "SELECT * FROM `bags` WHERE vaultID = '".$GLOBALS['vault']."'";
					$r2 = $GLOBALS['mysqli']->query($q2);
					while ($d1 = $r2->fetch_assoc()) {
						if($d1['bagID'] == $loc){ $selected = "selected='selected'"; } else { $selected = ""; }
						echo "<option value='".$d1['bagID']."' ".$selected.">".$d1['bagName']."</option>";
					}
			echo "</select>
				</div>
				<div class='row'>
					<div class='col-xs-12'>
						<button class='form-control btn btn-success' id='saveGJA' value='".$id."'>Save</button>
					</div>
				</div>";
		
		}

	   /* free results */
	   $stmt->free_result();

	   /* close statement */
	   $stmt->close();
	}

	function saveGJA($item){
		$q1 = "UPDATE `gemsjewelryart` SET gjaName = ?, gjaQty = ?, gjaValue = ?, gjaLoc = ?, changeBy = '".$GLOBALS['user']."' WHERE gjaId = '".$item[0]."'";
		$stmt = $GLOBALS['mysqli']->prepare($q1);
		$stmt->bind_param('siii',$item[1],$item[2],$item[3],$item[4]);
		$stmt->execute();
	}
?>