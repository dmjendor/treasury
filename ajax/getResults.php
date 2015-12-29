<?php
	INCLUDE '../functions.php';
	global $tInc;
	$GLOBALS['tInc'] = 0;

	if(!empty($_POST['table'])) {
		$tIn = $_POST['table'];
		$tVal = $_POST['value'];
		if(!empty($_POST['tInc'])){
			$GLOBALS['tInc'] = 1;
		}
		$result = call_user_func($tIn,$tVal);
	}
	

	if(!empty($_GET['table'])){

		$tIn = $_GET['table'];
		$tVal = $_GET['value'];
		if(!empty($_GET['tInc'])){
			$GLOBALS['tInc'] = 1;
		}
		$result = call_user_func($tIn,$tVal);
	}
	
/* close connection */
$mysqli->close();
?>
