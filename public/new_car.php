<?php
	require_once '../includes/session.php';
	require_once '../includes/db_connection.php';
	require_once '../includes/functions.php';
	require_once '../includes/validation_functions.php';
	confirm_logged_in();
	
	if ( isset( $_POST["submit"] ) ) {
		
		// Validations
		$required_fields = array("make", "model", "color", "license_plate", "status");
		validate_presences($required_fields);
		
		if (empty($errors)) {
			
			// Cars
			$make = escape_string(ucwords($_POST["make"]));
			$model = escape_string(ucwords($_POST["model"]));
			$color = escape_string(ucwords($_POST["color"]));
			$license_plate = escape_string($_POST["license_plate"]);
			
			$initial_query = "INSERT INTO cars (make, model, color, license_plate) VALUES ";
			$initial_query .= "('$make', '$model', '$color', '$license_plate');";
			$initial_result = mysqli_query($connection, $initial_query);
			
			if (empty($errors)) {
				
				$new_car_id = mysqli_insert_id($connection);
				
				// Incidents
				$status = escape_string($_POST["status"]);
				$description = escape_string($_POST["description"]);
				
				$query = "INSERT INTO incidents (car_id, status, description) VALUES ";
				$query .= "($new_car_id, '$status', '$description');";	
				
				$result = mysqli_multi_query($connection, $query);
				
				if ($result) {
					$_SESSION["class"] = "success"; 
					$_SESSION["message"] = "Car created.";
					redirect_to("edit_car.php?car=$new_car_id");
				} else {
					$_SESSION["class"] = "failure"; 
					$_SESSION["message"] = "Car creation failed.";
				}
			} else {
				$_SESSION["class"] = "failure"; 
				$_SESSION["message"] = "Car creation failed.";				
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
			<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
			Add New Car
		</h2>
		
		<?php
		if (!empty($message)) {
			echo '<div class="message">' . htmlentities($message) . '</div>'; 
		} ?>
		
		<form class="new-edit" enctype="multipart/form-data" action="new_car.php" method="post">
			<p>
				<span>Make:</span>
				<input type="text" class="text" name="make" value="<?php if (isset($_POST["make"])) { echo $_POST["make"]; } ?>" />
			</p>
			<p>
				<span>Model:</span>
				<input type="text" class="text" name="model" value="<?php if (isset($_POST["model"])) { echo $_POST["model"]; } ?>" />
			</p>
			<p>
				<span>Color:</span>
				<input type="text" class="text" name="color" value="<?php if (isset($_POST["color"])) { echo $_POST["color"]; } ?>" />
			</p>
			<p>
				<span>License Plate:</span>
				<input type="text" class="text" name="license_plate" value="<?php if (isset($_POST["license_plate"])) { echo $_POST["license_plate"]; } ?>" />
			</p>

			<?php $statuses = get_status_list(); ?>
			<p>
				<span>Status:</span>
				<select name="status">							
					<?php
					foreach ($statuses as $i => $status) {
						echo "<option value=\"$status\"";
						if (isset($_POST["status"]) && $_POST["status"] == $status) {
							echo " selected";
						}
						echo ">" . $status . "</option>";
					}
					?>
				</select>
			</p>
			

			<p>
				<span>Description:</span>
				<textarea name="description" class="textarea slim"><?php
				if (isset($_POST["description"])) { echo $_POST["description"]; }
				?></textarea>
			</p>
			
			<br/>
			<input type="submit" name="submit" value="Add Car" />
			<button type="button" class="cancel"><a href="manage_cars.php">Cancel</a></button>
		</form>
	</div>
</div>
<?php include '../includes/layout/footer.php'; ?>