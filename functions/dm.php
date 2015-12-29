<?php
	function getPrepList(){
		$q1 = "SELECT * FROM `vaultPrep` WHERE  vaultID = '".$GLOBALS['vault']."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		while ($row = $r1->fetch_assoc()) {
			echo "<li class='list-group-item prepList' data-id='".$row['vpID']."'>".$row['name']."</li>";
		}

	}

	function getPrepItems($id){
		$q1 = "SELECT * FROM `vaultPrep` WHERE  vpID = '".$id."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		while ($row = $r1->fetch_assoc()) {
			$q2 = "SELECT * FROM `prepCoin` WHERE vpID = '".$row['vpID']."'";
//			echo $q2."<br/>";
			$r2 = $GLOBALS['mysqli']->query($q2);
			$row_cnt = $r2->num_rows;
			if($row_cnt > 0){ echo "<ul class='list-group'>"; }
			while ($coin = $r2->fetch_assoc()) {
				$q3 = "SELECT * FROM `vaultCurrency` WHERE vcID = '".$coin['vcID']."'";
//				echo $q3."<br/>";
				$r3 = $GLOBALS['mysqli']->query($q3);
				while($cur = $r3->fetch_assoc()){
					echo "<li class='list-group-item' data-id='".$coin['coinID']."'>".$cur['currencyName']."<span class='badge'>".floatval($coin['value'])."</span></li>";
				}
			}
			if($row_cnt > 0){ echo "</ul>"; }

			$q4 = "SELECT * FROM `bags` WHERE vaultID = '".$GLOBALS['vault']."'";
			$r4 = $GLOBALS['mysqli']->query($q4);
			while ($bResult = $r4->fetch_assoc()) {
				echo "<h4>".$bResult['bagName']."</h4>";
				echo "<ul class='list-group gem-list' data-id='".$bResult['bagID']."'>";
				$q5 = "SELECT * FROM `prepGJA` WHERE vpID = '".$row['vpID']."' AND gjaLoc = '".$bResult['bagID']."'";
//				echo $q5."<br/>";
				$r5 = $GLOBALS['mysqli']->query($q5);
				while($gja = $r5->fetch_assoc()){
					echo "<li class='list-group-item' data-id='".$gja['gjaID']."'>".$gja['gjaName']." x".$gja['gjaQty']."<span class='badge'>".$gja['gjaValue']."</span></li>";
				}
				$q6 = "SELECT * FROM `prepItem` WHERE vpID = '".$row['vpID']."' AND tLoc = '".$bResult['bagID']."'";
//				echo $q6."<br/>";
				$r6 = $GLOBALS['mysqli']->query($q6);
				while ($item = $r6->fetch_assoc()) {
					echo "<li class='list-group-item' data-id='".$item['treasureID']."'>".$item['tName']." x".$item['tQty']."<span class='badge'>".$item['tValue']."</span></li>";
				}
			}
		}



	}





	function savePrep($item){
		$q1 = "UPDATE `treasure` SET tName = ?, tQty = ?, tValue = ?, tLoc = ?, changeBy = '".$GLOBALS['user']."' WHERE treasureID = '".$item[0]."'";
		$stmt = $GLOBALS['mysqli']->prepare($q1);
		$stmt->bind_param('siii',$item[1],$item[2],$item[3],$item[4]);
		$stmt->execute();
	}
?>