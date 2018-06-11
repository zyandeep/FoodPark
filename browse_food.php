<?php 

    require_once 'include/order_class.php';
    require_once 'include/dbcon.php';

    session_start();

    date_default_timezone_set("Asia/Kolkata");
    $dat = date("Y-m-d");

    if (isset($_GET['search']) and isset($_GET['category'])) {
        $food = trim($_GET['food']);
        $category = $_GET['category'];

        if (! empty($food)) {
            
            if($category != "ts") {
                $sql = "SELECT * FROM `item` WHERE i_name LIKE '%$food%' and category='$category' AND im_id NOT IN (SELECT im_id FROM todays_special WHERE date='$dat') AND status=1";
            }
            else {
                $sql = "SELECT item.im_id, i_name, category, price, image FROM item, todays_special WHERE item.i_name LIKE '%$food%' AND item.im_id=todays_special.im_id AND date='$dat' AND status=1";
            }

            if($result = mysqli_query($link, $sql)) {

                 // Fetching all the records
                 $rows = mysqli_fetch_all($result);

                 mysqli_free_result($result);
                 mysqli_close($link);
            } 
            else{
               // Redirect user to db error page
            }
        }
        // Get all the items of that category
        else {
            if ($category == "veg") {
               $sql = "SELECT * FROM item WHERE category='veg' AND im_id NOT IN (SELECT im_id FROM todays_special WHERE date='$dat') AND status=1";
            }
            elseif ($category == "non-veg") {
               $sql = "SELECT * FROM item WHERE category='non-veg' AND im_id NOT IN (SELECT im_id FROM todays_special WHERE date='$dat') AND status=1";   
            }
            elseif ($category == "other") {
               $sql = "SELECT * FROM item WHERE category='other' AND im_id NOT IN (SELECT im_id FROM todays_special WHERE date='$dat') AND status=1";
            }
            elseif ($category == "ts") {
               $sql = "SELECT item.im_id, i_name, category, price, image FROM item, todays_special WHERE item.im_id=todays_special.im_id AND date='$dat' AND status=1";
            }


            if($result = mysqli_query($link, $sql)) {

                 // Fetching all the records
                 $rows = mysqli_fetch_all($result);

                 mysqli_free_result($result);
                 mysqli_close($link);
            } 
            else{
               // Redirect user to db error page
            }
        }

    }
    elseif (isset($_GET['category'])) {

       $category = $_GET['category'];

       if ($category == "veg") {
          $sql = "SELECT * FROM item WHERE category='veg' AND im_id NOT IN (SELECT im_id FROM todays_special WHERE date='$dat') AND status=1";
       }
       elseif ($category == "non-veg") {
          $sql = "SELECT * FROM item WHERE category='non-veg' AND im_id NOT IN (SELECT im_id FROM todays_special WHERE date='$dat') AND status=1";   
       }
       elseif ($category == "other") {
          $sql = "SELECT * FROM item WHERE category='other' AND im_id NOT IN (SELECT im_id FROM todays_special WHERE date='$dat') AND status=1";
       }
       elseif ($category == "ts") {
          $sql = "SELECT item.im_id, i_name, category, price, image FROM item, todays_special WHERE item.im_id=todays_special.im_id AND date='$dat' AND status=1";
       }


       if($result = mysqli_query($link, $sql)) {

            // Fetching all the records
            $rows = mysqli_fetch_all($result);

            mysqli_free_result($result);
            mysqli_close($link);
       } 
       else{
          // Redirect user to db error page
       }

    }
    
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">

    <title>FoodPark | Browse Food</title>
  </head>
  <body>
    
    <div class="container">
      
      <?php 
        require 'include/header.php';
        
        if (empty($_SESSION["c_id"]) ):
      ?>

        <div class="alert alert-info" role="alert">
            <strong> You must login first to buy food.</strong>  
        </div>
    
       <?php endif; ?> 


      
      <?php if(isset($_GET['item_added']) and $_GET['item_added'] == "true"): ?>

       <div class="alert alert-info alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>

           <strong> Selected item has been added to cart </strong>  
       </div>
      
      <?php endif; ?> 



     <?php if(isset($_SESSION["c_id"]) and !empty($_GET['err_quan'])): ?>

      <div class="alert alert-danger alert-dismissible fade show" role="alert">
           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>

          <strong> Please enter a valid quantity </strong>  
      </div>
     
     <?php endif; ?> 



      <?php if(isset($_SESSION["c_id"])): ?>

        <div class="alert alert-info" role="alert">
            <strong> Maximum 30 of each food item is allowed </strong>  
        </div>

        <div class="container mb-2">
            <div class="row">
                <div class="col"></div>
                <div class="col">
                    <form method="GET" action="browse_food.php" class="form-inline">
                        <input type="text" name="food" class="form-control" placeholder="Search Food" value="<?php echo (empty($food))? "" : $food ;?>">
                        <input type="hidden" name="category" value="<?php echo $_GET['category']; ?>">

                        <input type="submit" name="search" class="btn btn-primary ml-2" value="Search">
                    </form>
                </div>
                <div class="col"></div>
            </div>
        </div>



       <div class="container">
           <div class="row">

               <div class="col-sm-9">
            
       <?php endif; ?>


                    <?php if (!empty($rows)): ?>

                        <div class="table-responsive">

                          <table class="table table-hover">
                              <thead class="thead-dark">
                                  <tr>
                                      <th>Image</th>
                                      <th>Item</th>
                                      <th>Price</th>
                                    
                                      <?php
                                        echo (isset($_SESSION["c_id"])) ? "<th>Quantity</th>" : ''; 
                                       ?>
                                  </tr>
                              </thead>
                              <tbody>
                                  
                                  <?php  foreach ($rows as $row): ?>
                                    
                                    <tr>
                                        <td> <?php echo '<img class="rounded" src="' . "$row[4]".'">';?></td>
                                        <td><strong> <?php echo $row[1]; ?> </strong></td>
                                        <td><strong>Rs. <?php echo number_format($row[3]); ?> </strong></td>
                                        
                                        <?php if (isset($_SESSION["c_id"])): ?>
                                            <td> 

                                                <?php

                                                    $placed = false;

                                                    if(! empty($_SESSION['orders'])) {

                                                        foreach ($_SESSION['orders'] as $order){
                                                            if ($order->getID() == $row[0]) {
                                                                $placed = true;
                                                                break;
                                                            }
                                                        }
                                                    } 
                                                ?>
                                                
                                               <?php if($placed === true): ?>
                                                    <span class="text-muted">Already ordered.</span>

                                               <?php else: ?>

                                                    <form  class="form-inline order-form" method="POST" action="take_order.php">

                                                     <input type="text" class="form-control form-control-sm mr-2" name="quantity">


                                                     <input type="hidden" name="im_id" value="<?php echo $row[0];?>">
                                                     <input type="hidden" name="i_name" value="<?php echo $row[1];?>">
                                                     <input type="hidden" name="price" value="<?php echo $row[3];?>">
                                                     <input type="hidden" name="category" value="<?php echo $category; ?>">

                                                      <button class="btn btn-primary" type="submit" name="buy">Order</button>
                                                    </form>

                                               <?php endif; ?>                                               
                                            </td>
                                        <?php endif; ?>
                                               
                                         
                                    </tr>

                                  <?php endforeach; ?>

                              </tbody>
                          </table>
                        </div>

                    <?php else: ?>

                        <h2 class="text-muted text-center m-5 p-5 "> No item for this category </h2>

                    <?php endif; ?> 


      <?php if(isset($_SESSION["c_id"])): ?>

                </div>
               
                   <div class="col-sm-3">
                       <div class="card bg-light border-info mt-2 sticky-top">
                            <div class="card-header"> Make Final Payment </div>
                            <div class="card-body">
                               <a href="make_payment.php" class="btn btn-primary btn-block"> Proceed </a>
                            </div>
                        </div>
                   </div>
           </div>
       </div>

      <?php endif ?>


      <?php 
        require 'include/footer.php';
      ?> 

    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>