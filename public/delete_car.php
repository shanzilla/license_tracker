<?php 
	require_once '../includes/session.php';
	require_once '../includes/db_connection.php';
	require_once '../includes/functions.php';

	find_selection();
	if (!$current_car) {
		redirect_to('manage_cars.php');
	}
	
	$car_id = $current_car["car_id"];
	
	$query = "DELETE FROM cars WHERE car_id = $car_id LIMIT 1;";
	$query .= "DELETE FROM incidents WHERE car_id = $car_id LIMIT 1;";
	
	$result = mysqli_multi_query($connection, $query);
	
	if ($result) {
		$_SESSION["class"] = "success";
		$_SESSION["message"] = "Car deleted.";
		redirect_to('manage_cars.php');
	} else {
		$_SESSION["class"] = "failure";
		$_SESSION["message"] = "Car deletion failed.";
		redirect_to("manage_cars.php");
	}
?>