<?php 

if (isset($_POST['submit'])) {

require_once '../include/dbcon.php';

	// Need input validation
	$user_name = $_POST['user_name'];
	$password = $_POST['password'];

	$sql = "SELECT * FROM admin WHERE user_name = '$user_name' and password = '$password'";

	if ($result = mysqli_query($link, $sql)) {
		if (mysqli_num_rows($result) === 1) {

			// Valid login

			$row = mysqli_fetch_assoc($result);
			session_start();

			$_SESSION['a_id']= $row['a_id'];
			$_SESSION['username']= $row['user_name'];

			// Send customer to his account
			header('Location: admin_home.php');
		
			}
		else {
			// Invalid login
			header('Location: index.php?error=login');
		}
	}
	
}
elseif (isset($_GET['logout']) and $_GET['logout'] == true)  {
	// Starting session
	session_start();

	// Destroying session completely
	unset($_SESSION['a_id']);
	unset($_SESSION['username']);

	header('Location: index.php');
}

else {
	header('Location:index.php');
}