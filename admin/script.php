<?php 

require '../include/dbcon.php';

$sql = "SELECT COUNT(*) as unread FROM bill WHERE status=0";

if($result = mysqli_query($link, $sql)){
	$row = mysqli_fetch_assoc($result);

	if ($row['unread'] > 0) {
		echo 'New orders <span class="badge badge-light">'. $row['unread'] .'</span>';
	}
}