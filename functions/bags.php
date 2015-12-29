<?php
	function bagMan(){
		$q1 = "SELECT * FROM `bags` WHERE vaultID = '".$GLOBALS['vault']."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		echo "
		<div class='col-xs-6'>
			<div class='panel'>
				<ul class='list-group bagList'>";
				while ($bResult = $r1->fetch_assoc()) {
					$i++;
					echo "<li class='list-group-item'>
							".$bResult['bagName']."
							<button type='button' class='pull-right removeBag btn btn-danger btn-xs' id='".$i."d' title='Remove Bag' value='".$bResult['bagID']."'>
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
				echo "<option value='".$bResult['bagID']."'>".$bResult['bagName']."</option>";
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
		$q1 = "SELECT * FROM `bagList` WHERE bagID = ".$bag;
		$r1 = $GLOBALS['mysqli']->query($q1);
		$bR = $r1->fetch_assoc();

		$q2 = "INSERT INTO `bags` (`bagName`,`changeBy`,`vaultID`) VALUES ('".$bR['bagName']."','".$GLOBALS['user']."', '".$GLOBALS['vault']."')";
		$r2 = $GLOBALS['mysqli']->query($q2);
		getBags();
	}

	function addBag($bag){
		$q2 = "INSERT INTO `bags` (`bagName`,`changeBy`,`vaultID`) VALUES ( ?,'".$GLOBALS['user']."','".$GLOBALS['vault']."')";
		$stmt = $GLOBALS['mysqli']->prepare($q2);
		$stmt->bind_param('s',$bag);
		$stmt->execute();
		getBags();
	}

	function removeBag($bag){
		$q2 = "DELETE FROM `bags` WHERE `bagID` = ".$bag;
		$r2 = $GLOBALS['mysqli']->query($q2);
		getBags();
	}

	function getBags(){
		$q1 = "SELECT * FROM `bags` WHERE vaultID = '".$GLOBALS['vault']."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		while ($bR = $r1->fetch_assoc()) {
			$i++;
			echo "<li class='list-group-item'>
					".$bR['bagName']."
					<button type='button' class='pull-right removeBag btn btn-danger btn-xs' id='".$i."d' title='Remove Bag' value='".$bR['bagID']."'>
						<span class='glyphicon glyphicon-minus'></span>
					</button></li>";
		}
	}

	function bagList(){
		$qLoc = "SELECT * FROM `bags` WHERE vaultID = '".$GLOBALS['vault']."'";
		$result = $GLOBALS['mysqli']->query($qLoc);
		while ($row = $result->fetch_assoc()) {
			echo "<option value='".$row['bagID']."'>".$row['bagName']."</option>";
		}
	}
?>