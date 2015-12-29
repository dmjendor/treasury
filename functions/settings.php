<?php

	function saveMarkup($item){
		$qS = "UPDATE `vaults` SET ".$item[1]." = '".$item[0]."' WHERE vaultID = '".$GLOBALS['vault']."'";
		$r2 = $GLOBALS['mysqli']->query($qS);
	}
	
	function saveCSS($item){
		$qS = "UPDATE `vaults` SET theme = '".$item."' WHERE vaultID = '".$GLOBALS['vault']."'";
		$r2 = $GLOBALS['mysqli']->query($qS);

	}
	function getCssSetting() {
		$qC = "SELECT * FROM `themes` ORDER BY themeName ASC";
		$rC = $GLOBALS['mysqli']->query($qC);

		while ($row = $rC->fetch_assoc()) {
			if($GLOBALS['vTheme'] == $row['themeFile']) { $selected = "selected"; } else { $selected = ""; }
			echo "<option value='".$row['themeFile']."' ".$selected.">".$row['themeName']."</option>";
		}

	}

	function getCurrencySettings(){
		$qC = "SELECT COUNT(*) FROM `vaultCurrency` WHERE vaultID = '".$GLOBALS['vault']."'";
		$rC = $GLOBALS['mysqli']->query($qC);
		if($rC->num_rows == 0){
			echo "<h4>You are currently using the Default currencies</h4>";
			echo "<button type='submit' class='createCurrency btn btn-primary btn-lg'>Create New Currency</button>";
		} else {
			$q1 = "SELECT * FROM `vaultCurrency` WHERE vaultID = '".$GLOBALS['vault']."'";
			$r1 = $GLOBALS['mysqli']->query($q1);
			while ($row = $r1->fetch_assoc()) {
				$i++;
				echo "
					<div class='row'>
						<div class='col-xs-4'>".$row['currencyName']."</div>
						<div class='col-xs-4'>".$row['abbrev']."</div>
						<div class='col-xs-4'>".$row['multiplier']."</div>
					</div>";
			}
		}
	}

	function currencyMan(){
		$q1 = "SELECT * FROM `vaultCurrency` WHERE vaultID = '".$GLOBALS['vault']."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		echo "
		<div class='col-xs-6'>
			<div class='panel'>
				<ul class='list-group currencyList'>";
			while ($row = $r1->fetch_assoc()) {
				$i++;
				echo "<li class='list-group-item'>
						".$row['currencyName']." - ".$row['abbrev']." - ".$row['multiplier']."
						<button type='button' class='pull-right removeCurrency btn btn-danger btn-xs' id='".$i."d' title='Remove Currency' value='".$row['vcID']."'>
							<span class='glyphicon glyphicon-minus'></span>
						</button></li>";
				}
		echo "
				</ul>
			</div>
		</div>
		<div class='col-xs-6'>
			<div class='form-group'>
				<input type='text' class='form-control' id='currencyName' placeholder='Currency Name'>
				<input type='text' class='form-control' id='abbrev' placeholder='Abbreviation'>
				<input type='text' class='form-control' id='multiplier' placeholder='Multiplier'>
				<button class='btn btn-success addCurrency form-control' type='button'>Add</button>
			</div>
		</div>
		<div class='col-xs-12'>
			<h4>Your lowest form of currency should have a multiplier of 1 and should be set as your base currency.</h4>
		</div>		
		";
	}

	function addCurrency($cur){
		$q2 = "INSERT INTO `vaultCurrency` (`vaultID`,`currencyName`,`abbrev`,`multiplier`) VALUES ( '".$GLOBALS['vault']."',?,?,?)";
		$stmt = $GLOBALS['mysqli']->prepare($q2);
		$stmt->bind_param('ssi',$cur[0],$cur[1],$cur[2]);
		$stmt->execute();
		getCurrency();
	}

	function removeCurrency($item){
		$q2 = "DELETE FROM `vaultCurrency` WHERE `vcID` = ".$item;
		$r2 = $GLOBALS['mysqli']->query($q2);
		getCurrency();
	}

	function getCurrency(){
		$q1 = "SELECT * FROM `vaultCurrency` WHERE vaultID = '".$GLOBALS['vault']."'";
		$r1 = $GLOBALS['mysqli']->query($q1);
		while ($bR = $r1->fetch_assoc()) {
			$i++;
			echo "<div class='col-xs-4'>".$bR['bagName']."</div>
					<button type='button' class='pull-right removeCurrency btn btn-danger btn-xs' id='".$i."d' title='Remove Bag' value='".$bR['bagID']."'>
						<span class='glyphicon glyphicon-minus'></span>
					</button></li>";
		}
	}

	function currencyList(){
		$q1 = "SELECT * FROM `vaultCurrency` WHERE vaultID = '".$GLOBALS['vault']."'";
		$result = $GLOBALS['mysqli']->query($qLoc);
		while ($row = $result->fetch_assoc()) {
			$i++;
			echo "
				<div class='row'>
					<div class='col-xs-4'>".$row['currencyName']."</div>
					<div class='col-xs-4'>".$row['abbrev']."</div>
					<div class='col-xs-4'>".$row['multiplier']."</div>
				</div>";
		}
	}

	function baseCurrencyList(){
		$q1 = "SELECT * FROM `vaultCurrency` WHERE vaultID = '".$GLOBALS['vault']."'";
		$result = $GLOBALS['mysqli']->query($q1);
		echo "<option value='0'>Default</option>";
		while ($row = $result->fetch_assoc()) {
			if($row['vcID'] == $GLOBALS['basCurID']){ $selected = "selected='selected'";} else { $selected = ""; }
			echo "<option ".$selected." value=".$row['vcID'].">".$row['currencyName']."</option>";
		}
	}

	function commonCurrencyList(){
		$q1 = "SELECT * FROM `vaultCurrency` WHERE vaultID = '".$GLOBALS['vault']."'";
		$result = $GLOBALS['mysqli']->query($q1);
		echo "<option value='0'>Default</option>";
		while ($row = $result->fetch_assoc()) {
			if($row['vcID'] == $GLOBALS['comCurID']){ $selected = "selected='selected'";} else { $selected = ""; }
			echo "<option ".$selected." value=".$row['vcID'].">".$row['currencyName']."</option>";
		}
	}

	function saveBaseCur($cur){
		$q1 = "UPDATE `vaults` SET baseCurID = ".$cur." WHERE vaultID = '".$GLOBALS['vault']."'";
		$result = $GLOBALS['mysqli']->query($q1);
	}
	function saveComCur($cur){
		$q1 = "UPDATE `vaults` SET cmCurID = ".$cur." WHERE vaultID = '".$GLOBALS['vault']."'";
		$result = $GLOBALS['mysqli']->query($q1);
	}
?>