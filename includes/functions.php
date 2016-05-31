<?php

	function redirect_to ($url) {
		header("Location: $url");
		exit;
	}
	
	function confirm_query ($result) {
		if (!$result) {
			die("Database query failed.");
		}
	}
	
	function escape_string($string) {
		
		global $connection;
		
		$escaped_string = mysqli_real_escape_string($connection, $string);
		return $escaped_string;
	}
	
	function find_all_cars () {
		
		global $connection;
		
		$query = "SELECT * FROM cars";
		$result = mysqli_query($connection, $query);
		confirm_query($result);
		
		return $result;
	}
	
	function find_car_by_id ($car_id) {
		
		global $connection;
		
		$query = "SELECT * FROM cars WHERE car_id = $car_id LIMIT 1";
		$result = mysqli_query($connection, $query);
		confirm_query($result);
		
		if ( $car = mysqli_fetch_assoc($result) ){
			return $car;
		} else {
			return null;
		}
	}

	function find_incident_by_car_id ($car_id) {
		
		global $connection;
		
		$query = "SELECT * FROM incidents WHERE car_id = $car_id LIMIT 1";
		$result = mysqli_query($connection, $query);
		confirm_query($result);
		
		if ( $incident = mysqli_fetch_assoc($result) ){
			return $incident;
		} else {
			return null;
		}
	}
	
	function find_selection () {
		
		global $current_car;
		
		if ( isset( $_GET["car"] ) ) {
			$current_car = find_car_by_id( $_GET["car"] );
		} else {
			$current_car = null;
		}
	}

	function find_all_users () {
		
		global $connection;
		
		$query = "SELECT * FROM users ORDER BY username ASC";
		$result = mysqli_query($connection, $query);
		confirm_query($result);
		
		return $result;
	}
	
	function find_user_by_id ($user_id) {
		
		global $connection;
		
		$query = "SELECT * FROM users WHERE user_id = $user_id LIMIT 1";
		$result = mysqli_query($connection, $query);
		confirm_query($result);
		
		if ( $user = mysqli_fetch_assoc($result) ){
			return $user;
		} else {
			return null;
		}
	}
	
	function find_user_by_username ($username) {
		
		global $connection;
		
		$username = escape_string($username);
		
		$query = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
		$result = mysqli_query($connection, $query);
		confirm_query($result);
		
		if ( $user = mysqli_fetch_assoc($result) ){
			return $user;
		} else {
			return null;
		}
	}
	
	function find_selected_user() {
		
		global $selected_user;
		
		if ( isset( $_GET["id"] ) ) {
			$selected_user = find_user_by_id( $_GET["id"] );
		} else {
			$selected_user = null;
		}
	}
	
	function only_one_admin() {
		
		global $connection;
		
		$query = "SELECT IF(COUNT(*) = 1,1,0) AS value FROM users WHERE privilege_level = 3";
		$result = mysqli_query($connection, $query);
		confirm_query($result);
		
		$bool = mysqli_fetch_assoc($result);
		
		return $bool["value"];
	}
	
	function is_admin ($user) {
		
		global $connection;
		
		$user_id = $user["user_id"];
		$query = "SELECT IF(privilege_level = 3,1,0) AS value FROM users WHERE user_id = $user_id";
		$result = mysqli_query($connection, $query);
		confirm_query($result);
		
		$bool = mysqli_fetch_assoc($result);
		
		return $bool["value"];
	}

	function logged_in () {
		return isset($_SESSION["user_id"]);
	}
	
	function confirm_logged_in () {
		
		if (!logged_in()) {
			redirect_to("index.php");
		}
	}
	
	function logged_in_as () {
		$privilege = check_privilege();
		$privileges = get_privilege_list();
		?>

		<div class="logged-in-container">
			<ul class="logged-in-as">
				<li class="name">
					Logged in as
					<?php
						echo htmlentities(trim($_SESSION["full_name"]));
						echo " (";
						foreach ($privileges as $i => $priv) {
							if ($_SESSION["privilege"] == $i + 1) { echo $priv; }
						}
						echo ")";
					?>
				</li>
				<li>
					<a href="manage_cars.php">Manage Cars</a> | 
					<?php if ($privilege == 3) { ?>
						<a href="manage_users.php">Manage Users</a> |
					<?php } ?>
					<a href="logout.php">Logout</a>
				</li> 
			</ul>
		</div>
		<?php
	}

	function get_status_list () {
		return array("Ran checkpoint", "Insufficient funds", "Stolen car", "Other");
	}

	function get_privilege_list () {
		return array("Tracker", "Supervisor", "Administrator");
	}

	function get_privilege_by_level ($privilege_level) {

		$privileges = get_privilege_list();

		return $privileges[ $privilege_level - 1 ];
	}

	function check_privilege () {
		if (isset($_SESSION["privilege"])) {
			return $_SESSION["privilege"];
		} else {
			return 0;
		}
	}

	function check_user_management_access () {

		$privilege = check_privilege();
		
		if ($privilege != 3) {
			redirect_to('index.php');
		}
	}
	
	function print_header ($type) {		
		include "layout/header.php";
		include "layout/banner-$type.php";
	}
	
	function ends_with($needle, $haystack) {
		
	    $length = strlen($needle);
	    if ($length == 0) {
	        return true;
	    }
	
	    return (substr($haystack, -$length) === $needle);
	}
	
	function form_errors($errors=array()) {
	
		$output = "";
		if (!empty($errors)) {
			$output .= "<div class=\"errors\">";
			$output .= '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>';
			$output .= "Please fix the following errors:";
			$output .= "<ul>";
				foreach ($errors as $key => $error_msg) {
					$output .= "<li>" . htmlentities($error_msg) . "</li>";
				}
			$output .= "</ul>";
			$output .= "</div>";
		}
	
		echo $output;
	}
	
	function encrypt_pass ($password) {

		$hash_format = "$2y$10$";
		$length = 22;
		$salt = generate_salt($length);
		$format_and_salt = $hash_format . $salt;
		$hash = crypt($password, $format_and_salt);
		
		return $hash;
	}
	
	function generate_salt($length) {
		
		$unique_random_str = md5(uniqid(mt_rand(), TRUE));
		$base64_str = base64_encode($unique_random_str);
		
		// Base 64 encoding allows '+' chars which are not allowed in salts
		$modified_base64_str = str_replace("+", ".", $base64_str);
		$salt = substr($modified_base64_str, 0, $length);
		
		return $salt;
	}
	
	function password_check ($password, $existing_hash) {
		
		$new_hash = crypt($password, $existing_hash);
		$new_hash = substr($new_hash, 0, 32);
		if ($new_hash === $existing_hash) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function attempt_login ($username, $password) {
		
		$user = find_user_by_username($username);
		if ($user) {
			if (password_check($password, $user["password"])) {
				return $user;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
?>