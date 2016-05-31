<?php
	require_once '../includes/session.php';
	require_once '../includes/db_connection.php';
	require_once '../includes/functions.php';
	require_once '../includes/validation_functions.php';
	confirm_logged_in();
	
	find_selection();
	if (!$current_car) {
		redirect_to('manage_cars.php');
	}
	
	$current_incident = find_incident_by_car_id( $current_car["car_id"] );	

	if ( isset( $_POST["submit"] ) ) {
		
		// Validations	
		$required_fields = array("make", "model", "color", "license_plate", "status");
		validate_presences($required_fields);
		
		if (empty($errors)) {

			// Cars
			$car_id = $current_car["car_id"];
			$make = escape_string(ucwords($_POST["make"]));
			$model = escape_string(ucwords($_POST["model"]));
			$color = escape_string(ucwords($_POST["color"]));
			$license_plate = escape_string($_POST["license_plate"]);

			$query = "UPDATE cars SET ";
			$query .= "make = '$make', ";
			$query .= "model = '$model', ";
			$query .= "color = '$color', ";
			$query .= "license_plate = '$license_plate' ";
			$query .= "WHERE car_id = $car_id ";
			$query .= "LIMIT 1;";
			
			//Incidents
			$status = escape_string($_POST["status"]);
			$description = escape_string($_POST["description"]);
			
			$query .= "UPDATE incidents SET ";
			$query .= "status = '$status', ";
			$query .= "description = '$description' ";
			$query .= "WHERE car_id = $car_id ";
			$query .= "LIMIT 1;";			
			
			$result = mysqli_multi_query($connection, $query);
			
			if ($result) {
				$_SESSION["class"] = "success";
				$_SESSION["message"] = "Car updated."; 
				redirect_to("edit_car.php?car=$car_id");
			} else {
				$_SESSION["class"] = "failure"; 
				$_SESSION["message"] = "Car update failed.";
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
				<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
				Edit Car
			</h2>
			
			<?php
			if (!empty($message)) {
				echo '<div class="message">' . htmlentities($message) . '</div>'; 
			} ?>
			
			<form class="new-edit"  enctype="multipart/form-data" action="edit_car.php?car=<?php echo urlencode($current_car["car_id"]); ?>" method="post">
				<p>
					<span>Make:</span>
					<input type="text" class="text" name="make" value="<?php echo $current_car["make"]; ?>" />
				</p>
				<p>
					<span>Model:</span>
					<input type="text" class="text" name="model" value="<?php echo $current_car["model"]; ?>" />
				</p>
				<p>
					<span>Color:</span>
					<input type="text" class="text" name="color" value="<?php echo $current_car["color"]; ?>" />
				</p>
				<p>
					<span>License Plate:</span>
					<input type="text" class="text" name="license_plate" value="<?php echo $current_car["license_plate"]; ?>" />
				</p>

				<?php $statuses = get_status_list(); ?>
				<p>
					<span>Status:</span>
					<select name="status">							
						<?php
						foreach ($statuses as $i => $status) {
							echo "<option value=\"$status\"";
							if ($current_incident["status"] == $status) {
								echo " selected";
							}
							echo ">" . $status . "</option>";
						}
						?>
					</select>
				</p>
				<p>
					<span>Description:</span>
					<textarea name="description" class="textarea slim"><?php echo $current_incident["description"]; ?></textarea>
				</p>
				
				<br/>
				<input type="submit" name="submit" value="Save" />
				<button type="button" class="delete"><a href="delete_car.php?car=<?php echo urlencode($current_car["car_id"])?>" onclick="return confirm('Are you sure?');">Delete</a></button>
				<button type="button" class="cancel"><a href="manage_cars.php">Cancel</a></button>
			</form>
		</div>
	</div>
<?php include '../includes/layout/footer.php'; ?>