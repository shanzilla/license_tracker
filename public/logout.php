<?php
	require_once '../includes/session.php';
	require_once '../includes/functions.php';
	
	$_SESSION["user_id"] = null;
	$_SESSION["full_name"] = null;
	$_SESSION["privilege"] = null;
	
	redirect_to("index.php");
?>