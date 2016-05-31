<?php
	require_once '../includes/session.php';
	require_once '../includes/db_connection.php';
	require_once '../includes/functions.php';
	confirm_logged_in();
	
	find_selection();
	print_header("admin");
?>

	<div class="container-fluid admin">
		<div class="admin admin-content manage-inventory">
			
			<?php
				logged_in_as();
				display_message();
			?>
			
			<h2>
				<span class="glyphicon glyphicon-list" aria-hidden="true"></span>
				Manage Cars
				<span class="add-new">
					<a href="new_car.php">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
						Add New
					</a>
				</span>
			</h2>

			<table class="cars">
				<tr>
					<th>Make</th>
					<th>Model</th>
					<th>Color</th>
					<th>License Plate</th>
					<th>Actions</th>
				</tr>
				<?php
				$cars = find_all_cars();

				foreach ($cars as $car) {
					$car_id = urlencode($car["car_id"]); ?>

					<tr>
						<td><?php echo $car["make"]; ?></td>
						<td><?php echo $car["model"]; ?></td>
						<td><?php echo $car["color"]; ?></td>
						<td><?php echo $car["license_plate"]; ?></td>
						<td>
							<a href="edit_car.php?car=<?php echo $car_id; ?>">
								<span class="glyphicon glyphicon-pencil edit" aria-hidden="true"></span>
								Edit
							</a> &nbsp;
							<a href="delete_car.php?car=<?php echo $car_id; ?>" onclick="return confirm('Are you sure?');">
								<span class="glyphicon glyphicon-remove delete" aria-hidden="true"></span>
								Delete
							</a>
						</td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
<?php include '../includes/layout/footer.php'; ?>
