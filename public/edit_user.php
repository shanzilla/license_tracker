<?php
	require_once '../includes/session.php';
	require_once '../includes/db_connection.php';
	require_once '../includes/functions.php';
	require_once '../includes/validation_functions.php';
	confirm_logged_in();
	check_user_management_access();

	find_selected_user();
	if (!$selected_user) {
		redirect_to('manage_users.php');
	}
	
	if ( isset( $_POST["submit"] ) ) {
		$user_id = $selected_user["user_id"];
		
		if (is_admin($selected_user) && only_one_admin() && $_POST["privilege"] != 3) {
			$_SESSION["class"] = "failure";
			$_SESSION["message"] = "Can't change user type of the only Administrator."; 
			redirect_to("edit_user.php?id=$user_id");
		}
		
		// Validations	
		$required_fields = array("username", "email", "first_name");
		validate_presences($required_fields);
		
		$fields_w_max_lengths = array("username" => 12);
		validate_max_lengths($fields_w_max_lengths);
		
		if (empty($errors)) {
			$new_pass = false;
			// Process form
			$username = escape_string($_POST["username"]);
			if (isset($_POST["password"]) && $_POST["password"] != "") {
				$hashed_pass = encrypt_pass($_POST["password"]);
				$new_pass = true;
			}
			$fname = escape_string($_POST["first_name"]);
			$lname = escape_string($_POST["last_name"]);
			$email = escape_string($_POST["email"]);
			$privilege_level = (int) $_POST["privilege"];
			
			$query = "UPDATE users SET ";
			$query .= "username = '$username', ";
			$query .= "first_name = '$fname', ";
			if ($lname) { $query .= "last_name = '$lname', "; }
			if ($new_pass) { $query .= "password = '$hashed_pass', "; }
			$query .= "email = '$email', ";
			$query .= "privilege_level = $privilege_level ";
			$query .= "WHERE user_id = $user_id ";
			$query .= "LIMIT 1";
			$result = mysqli_query($connection, $query);
			
			if ($result && mysqli_affected_rows($connection) >= 0) {
				$_SESSION["class"] = "success";
				$_SESSION["message"] = "User updated."; 
				redirect_to("manage_users.php");
			} else {
				$_SESSION["class"] = "failure"; 
				$_SESSION["message"] = "User update failed.";
			}
		}		
	}
	
	print_header("admin");
?>

	<div class="container-fluid admin">
		<div class="admin admin-content">
		
		<?php
			logged_in_as();
			display_message();
			form_errors($errors);
		?>
			
			<h2>
				<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
				Edit User
			</h2>
			
			<?php
			if (!empty($message)) {
				echo '<div class="message">' . htmlentities($message) . '</div>'; 
			}
			
			//echo form_errors
			
			?>
			
			<form class="new-edit" action="edit_user.php?id=<?php echo urlencode($selected_user["user_id"]); ?>" method="post">
				<p>
					<span>Username:</span>
					<input type="text" class="text" name="username" value="<?php echo $selected_user["username"]; ?>" />
				</p>
				<p>
					<span>First Name:</span>
					<input type="text" class="text" name="first_name" value="<?php echo $selected_user["first_name"]; ?>" />
				</p>
				<p>
					<span>Last Name:</span>
					<input type="text" class="text" name="last_name" value="<?php echo $selected_user["last_name"]; ?>" />
				</p>
				<p>
					<span>Password:</span>
					<input type="password" class="text" name="password" />
				</p>
				<p>
					<span>Email:</span>
					<input type="text" class="text" name="email" value="<?php echo $selected_user["email"]; ?>" />
				</p>

				<?php $privileges = get_privilege_list(); ?>
				<p>
					<span>User Type:</span>
					<select name="privilege">							
						<?php
						foreach ($privileges as $i => $privilege) {
							echo "<option value=\"" . ( $i + 1 ) . "\"";
							if ($selected_user["privilege_level"] == ( $i + 1 )) {
								echo " selected";
							}
							echo ">" . $privilege . "</option>";
						}
						?>
					</select>
				</p>
				<br/>
				<input type="submit" name="submit" value="Save" />
				<button type="button" class="cancel"><a href="manage_users.php">Cancel</a></button>
			</form>
		</div>
	</div>
<?php include '../includes/layout/footer.php'; ?>