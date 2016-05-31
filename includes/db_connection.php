<?php
	DEFINE("DB_SERVER", "localhost");
	DEFINE("DB_USER", "root");
	DEFINE("DB_PASS", "root");
	DEFINE("DB_NAME", "license_tracker");

	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	
	if (mysqli_connect_errno()) {
		die(
			"Database connection failed: " .
			mysqli_connect_error() . " (" .
			mysqli_errno() . ")"
		);
	}
?>