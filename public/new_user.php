<?php
	require_once '../includes/session.php';
	require_once '../includes/db_connection.php';
	require_once '../includes/functions.php';
	require_once '../includes/validation_functions.php';
	confirm_logged_in();
	check_user_management_access();

	if ( isset( $_POST["submit"] ) ) {
		
		// Validations
		$required_fields = array("username", "password", "email", "first_name");
		validate_presences($required_fields);
		
		$fields_w_max_lengths = array("username" => 12);
		validate_max_lengths($fields_w_max_lengths);
		
		if (empty($errors)) {
			
			// Process form
			$username = escape_string($_POST["username"]);
			$hashed_pass = encrypt_pass($_POST["password"]);
			$fname = escape_string($_POST["first_name"]);
			$lname = escape_string($_POST["last_name"]);
			$email = escape_string($_POST["email"]);
			$privilege_level = (int) $_POST["privilege"];
			
			$query = "INSERT INTO users (username, password, first_name, email, privilege_level";
				if ($lname) { $query .= ", last_name"; }
			$query .= ") VALUES ('$username', '$hashed_pass', '$fname', '$email', $privilege_level";
				if ($lname) { $query .= ", '$lname'"; }
			$query .= ")";
			
			$result = mysqli_query($connection, $query);
			
			if ($result) {
				$_SESSION["class"] = "success"; 
				$_SESSION["message"] = "User created.";
				redirect_to('manage_users.php');
			} else {
				$_SESSION["class"] = "failure"; 
				$_SESSION["message"] = "User creation failed.";
			}
		}
		
	}

	print_header("admin");
?>

	<div class="container-fluid admin">
		<div class="row admin admin-content">
		<?php
			logged_in_as();
			display_message();
			form_errors($errors);
		?>
			
			<h2>
				<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
				Add New User
			</h2>
			
			<form class="new-edit" action="new_user.php" method="post">
				<p>
					<span>Username:</span>
					<input type="text" class="text" name="username" 
					value="<?php if (isset($_POST["submit"])) { echo $_POST["username"]; }?>" />
				</p>
				<p>
					<span>Password:</span>
					<input type="password" class="text" name="password" value="" />
				</p>
				<p>
					<span>First Name:</span>
					<input type="text" class="text" name="first_name" 
					value="<?php if (isset($_POST["first_name"])) { echo $_POST["first_name"]; }?>" />
				</p>
				<p>
					<span>Last Name:</span>
					<input type="text" class="text" name="last_name" 
					value="<?php if (isset($_POST["submit"])) { echo $_POST["last_name"]; }?>" />
				</p>
				<p>
					<span>Email:</span>
					<input type="text" class="text" name="email" 
					value="<?php if (isset($_POST["email"])) { echo $_POST["email"]; }?>" />
				</p>

				<?php $privileges = get_privilege_list(); ?>
				<p>
					<span>User Type:</span>
					<select name="privilege">							
						<?php
						foreach ($privileges as $i => $privilege) {
							echo "<option value=\"" . ( $i + 1 ) . "\"";
							if (isset($_POST["privilege"])) {
								if ($_POST["privilege"] == ( $i + 1 )) {
									echo " selected";
								}
							}
							echo ">" . $privilege . "</option>";
						}
						?>
					</select>
				</p>
				<br/>
				<input type="submit" name="submit" value="Add User" />
				<button type="button" class="cancel"><a href="manage_users.php">Cancel</a></button>
			</form>
			<br/>
		</div>
	</div>
<?php include '../includes/layout/footer.php'; ?>