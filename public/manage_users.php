<?php
	require_once '../includes/session.php';
	require_once '../includes/db_connection.php';
	require_once '../includes/functions.php';
	confirm_logged_in();
	check_user_management_access();

	print_header("admin");
?>

	<div class="container-fluid admin">
		<div class="admin admin-content">
			<?php
				logged_in_as();
				display_message();
			?>
			
			<h2>
				<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
				Manage Users
				<span class="add-new">
					<a href="new_user.php">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
						Add New
					</a>
				</span>
			</h2>

			<table class="users">
				<tr>
					<th>Username</th>
					<th>Name</th>
					<th>Email</th>
					<th>Privilege</th>
					<th>Actions</th>
				</tr>

				<?php
				$users = find_all_users();
				
				foreach ($users as $user) {
					$user_id = urlencode($user["user_id"]); ?>
				
					<tr>
						<td><?php echo $user["username"]; ?></td>
						<td><?php echo $user["first_name"] . " " . $user["last_name"]; ?></td>
						<td><?php echo $user["email"]; ?></td>
						<td><?php echo get_privilege_by_level($user["privilege_level"]); ?></td>
						<td class="col-sm-6">
							<a href="edit_user.php?id=<?php echo $user_id; ?>">
								<span class="glyphicon glyphicon-pencil edit" aria-hidden="true"></span>
								Edit
							</a> &nbsp;
							<a href="delete_user.php?id=<?php echo $user_id; ?>" onclick="return confirm('Are you sure?');">
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