<?php 

require 'include/dbcon.php';

if (!empty($_GET['ph_no'])) {
	$ph_no = $_GET['ph_no'];
	
	
	$sql = "SELECT * FROM customer WHERE phone_no='$ph_no'";

	if($result = mysqli_query($link, $sql)){
		$row = mysqli_fetch_assoc($result);
		$count = mysqli_num_rows($result);

		if (isset($_GET['c_id'])) {
			$c_id = $_GET['c_id'];

			if ($row['c_id'] != $c_id) {
				echo "Phone No already registered";
			}
		}
		else {
			if ($count > 0) {
				echo "Phone No already registered";
			}
		}
	}
}