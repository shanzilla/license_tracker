	<footer>
 		<small>&copy; <?php echo date('Y'); ?></small>
	</footer>
</div> 

	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>

<?php if (isset($connection)) { mysqli_close($connection); } ?>