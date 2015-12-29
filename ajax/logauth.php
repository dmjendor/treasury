<?php
	require('../wp-load.php');

	$user_login     = esc_attr($_POST["user"]);
	$user_password  = esc_attr($_POST["pass"]);
	$rmbr			= esc_attr($_POST['rmbr']);

	$user_data = array(
		'user_login'    =>      $user_login,
		'user_pass'     =>      $user_password,
	);

	// Inserting new user to the db
	//wp_insert_user( $user_data );

	$creds = array();
	$creds['user_login'] = $user_login;
	$creds['user_password'] = $user_password;
	if($rmbr = 1){
		$creds['remember'] = true;
	} else {
		$creds['remember'] = false;
	}

	$user = wp_signon( $creds, false );
	if (is_wp_error($user)) {
		$error = $user->get_error_message();
	} else {
		$userID = $user->ID;

		wp_set_current_user( $userID, $user_login );
		wp_set_auth_cookie( $userID, true, false );
		do_action( 'wp_login', $user_login );

		if ( is_user_logged_in() ) { 
			echo 'SUCCESS'; 
		} else { 
			echo 'FAILURE'; 
			if ($error) {echo $error; print_r($userdata); print_r($current_user);}		
		}
	}


			
?>
