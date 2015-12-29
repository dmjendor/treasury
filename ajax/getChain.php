<?php
	INCLUDE '../functions/treasureF.php';

	if(!empty($_GET['qClass'])) {
		$mArr = array();
		$q1 = "SELECT * FROM item_tree WHERE parent = ".$_GET['qClass']." ORDER BY name ASC";
		$result = $GLOBALS['mysqli']->query($q1);
		while($row = $result->fetch_assoc()){
			$m2 = array($row['id'],$row['name']);
			array_push($mArr,$m2);
		}
		echo json_encode($mArr);
	}

	if(!empty($_GET['qType'])){
		$tArr = array();
		$q2 = "SELECT * FROM itemList WHERE parent = ".$_GET['qType']." ORDER BY itemName ASC";
		$result = $GLOBALS['mysqli']->query($q2);
		while($row = $result->fetch_assoc()){
			$t2 = array($row['itemId'],$row['itemName']);
			array_push($tArr,$t2);
		}
		echo json_encode($tArr);
	}

	if(!empty($_GET['qName'])){
		$tArr = array();
		$q1 = "SELECT * FROM itemList WHERE itemId = ".$_GET['qName'];
		$r1 = $GLOBALS['mysqli']->query($q1);
		$item = $r1->fetch_assoc();
		$vars = "";
		if($item['metal']==1){
			$vars .= " AND ( `metal` = '1' ";
			if($item['wood']==1){
				$vars .= " OR `wood` = '1' ";
			}
			if($item['leather']==1){
				$vars .= " OR `leather` = '1' ";
			}
			$vars .= ")";
		} else if($item['wood']==1){
			$vars .= " AND  ( `wood` = '1' ";
			if($item['leather']==1){
				$vars .= " OR `leather` = '1' ";
			}
			$vars .= ")";
		} else if($item['leather']==1){
			$vars .= " AND  ( `leather` = '1' )";
		}

		$q2 = "SELECT * FROM `modifiers` WHERE `parent` LIKE '%".$item['parent']."%'".$vars." ORDER BY name ASC";
		$r2 = $GLOBALS['mysqli']->query($q2);
		while($row = $r2->fetch_assoc()){
			$t2 = array($row['modId'],$row['name']);
			array_push($tArr,$t2);
		}
		echo json_encode($tArr);
	}

	if(!empty($_GET['gClass'])) {
		$mArr = array();
		$q1 = "SELECT * FROM gja_tree WHERE parent = ".$_GET['gClass']." ORDER BY name ASC";
		$result = $GLOBALS['mysqli']->query($q1);
		while($row = $result->fetch_assoc()){
			$m2 = array($row['id'],$row['name']);
			array_push($mArr,$m2);
		}
		echo json_encode($mArr);
	}

	if(!empty($_GET['gType'])){
		$tArr = array();
		$q2 = "SELECT * FROM gjaList WHERE parent LIKE '%".$_GET['gType']."%'ORDER BY itemName ASC";
		$result = $GLOBALS['mysqli']->query($q2);
		while($row = $result->fetch_assoc()){
			$title = $row['itemName']." (".$row['itemLVal']/$GLOBALS['curMult']."-".$row['itemHVal']/$GLOBALS['curMult'].")";
			$t2 = array($row['itemId'],$title);
			array_push($tArr,$t2);
		}
		echo json_encode($tArr);
	}
/* close connection */
$mysqli->close();
?>

