<?php
	require('../wp-load.php');

	$uid = $_POST['user'];
	$pwd = $_POST['pass'];

	$creds = array();
	$creds['user_login'] = 'example';
	$creds['user_password'] = 'plaintextpw';
	if(!empty($_POST['rmbr']){
		$creds['remember'] = true;
	} else {
		$creds['remember'] = false;
	}

	$user = wp_signon( $creds, false );
	if ( is_wp_error($user) ) {
		echo "0";
	} else {
		echo "1";
	}
		
?>
