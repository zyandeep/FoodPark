<?php 

    session_start();

    if (empty($_SESSION['a_id']) or empty($_SESSION['username'])) {
        header("Location: index.php"); 
    }    

    require_once '../include/dbcon.php';

    date_default_timezone_set("Asia/Kolkata");
    $dat = date("Y-m-d");

    if (isset($_POST['add-ts'])) {

    	$im_id = $_POST['im_id']; 
    	$i_name = $_POST['i_name'];
    	$category = $_POST['category'];

        $sql = "INSERT INTO todays_special(im_id, date) VALUES($im_id, '$dat')";

        if(mysqli_query($link, $sql)){
            header("Location: view_food.php?add=$i_name&category=$category");
        } 
        else{
            // DB Error
        }
    }
    elseif (isset($_POST['remove-ts'])) {

        $im_id = $_POST['im_id']; 
        $i_name = $_POST['i_name'];
        $category = $_POST['category'];
        $t_id = $_POST['t_id'];

        $sql = "UPDATE todays_special SET date=NULL WHERE t_id=$t_id";

        if(mysqli_query($link, $sql)){
            header("Location: view_food.php?remove=$i_name&category=$category");
        } 
        else{
            // DB Error
        }
    }
    elseif( isset($_POST['update']) ) {
        $im_id = $_POST['im_id'];

        // Forward the request to update_item.php
        include 'update_item.php'; 
    }