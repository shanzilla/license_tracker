<!-- Name: Shannon Lenise Fitzgerald -->
<?php
	require_once '../includes/session.php';
	require_once '../includes/db_connection.php';
	require_once '../includes/functions.php';
	require_once '../includes/validation_functions.php';

	if (logged_in()) {
		redirect_to("manage_cars.php");
	}
	
	if ( isset( $_POST["submit"] ) ) {
		
		// Validations
		$required_fields = array("username", "password");
		validate_presences($required_fields);
		
		if (empty($errors)) {
			
			// Process form
			$username = $_POST["username"];
			$password = $_POST["password"];
			
			$found_user = attempt_login($username, $password);
			
			if ($found_user) {
				$_SESSION["user_id"] = $found_user["user_id"];
				$_SESSION["full_name"] = $found_user["first_name"] . " " . $found_user["last_name"];
				$_SESSION["privilege"] = $found_user["privilege_level"];
				redirect_to('manage_cars.php');
			} else {
				$_SESSION["class"] = "failure"; 
				$_SESSION["message"] = "Username/password can't be found.";
			}
		}
		
	}

	print_header("admin");
?>
	<div class="container-fluid admin">
		<div class="admin admin-content">
				
			<?php
				display_message();
				form_errors($errors);
			?>
			<div class="login">
				<h2>
					<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
					Login
				</h2>
				
				<form class="new-edit" action="index.php" method="post">
					<p>
						<span>Username:</span>
						<input type="text" class="text" name="username" 
						value="<?php if (isset($_POST["username"])) echo htmlentities($_POST["username"]); ?>" />
					</p>
					<p>
						<span>Password:</span>
						<input type="password" class="text" name="password" value="" />
					</p>
					<br/>
					<input type="submit" name="submit" value="Login" />
				</form>
				<br/>
			</div>
		</div>
	</div>

<?php include '../includes/layout/footer.php'; ?>
