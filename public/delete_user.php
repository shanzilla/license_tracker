<?php 
	require_once '../includes/session.php';
	require_once '../includes/db_connection.php';
	require_once '../includes/functions.php';

	find_selected_user();
	if (!$selected_user) {
		redirect_to('manage_users.php');
	}

	if ( is_admin($selected_user) && only_one_admin() ) {
		$_SESSION["class"] = "failure";
		$_SESSION["message"] = "Can't delete the only Administrator.";
		redirect_to("manage_users.php");
	}
	
	$user_id = $selected_user["user_id"];
	
	$query = "DELETE FROM users WHERE user_id = $user_id LIMIT 1";
	$result = mysqli_query($connection, $query);
	
	if ($result && mysqli_affected_rows($connection) == 1) {
		$_SESSION["class"] = "success";
		$_SESSION["message"] = "User deleted.";
		redirect_to('manage_users.php');
	} else {
		$_SESSION["class"] = "faliure";
		$_SESSION["message"] = "User deletion failed.";
		redirect_to("manage_users.php");
	}
?>