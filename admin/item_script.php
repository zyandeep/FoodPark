<?php

require '../include/dbcon.php';

if (isset($_GET['i_name'])) {
	$i_name = $_GET['i_name'];

	$sql = "SELECT * FROM item WHERE i_name='$i_name'";

	if($result = mysqli_query($link, $sql)){
		$row = mysqli_fetch_assoc($result);
		$count = mysqli_num_rows($result);

		if ($count > 0) {

			if (isset($_GET['im_id']) and ($_GET['im_id'] != $row['im_id'])) {
				echo "This Food item already exist";
			}
			elseif(empty($_GET['im_id'])) {
				echo "This Food item already exist";
			}
		}

	}
}