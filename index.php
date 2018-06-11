<?php  session_start(); ?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">


    <title>Welcome to FoodPark</title>
  </head>

  <body>

    <div class="container">
    
      <?php require 'include/header.php'; ?>

   
      <?php if (isset($_GET['error']) and $_GET['error'] === 'login'): ?>
          
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>Oops! That was an invalid login.</strong> Try again
          </div>
          
      <?php endif; ?>


      <?php if (isset($_GET['order']) and $_GET['order'] === 'cancel' and isset($_SESSION['orders']) === false): ?>
          
          <div class="alert alert-info alert-dismissible fade show" role="alert">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>Your order has been canceled</strong>
          </div>
          
      <?php endif; ?>


     
     <?php if (!empty($_GET['cus_reg'])): ?>

        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>You've been registered successfully!</strong>
          Now, log in to buy food
        </div>

      <?php endif; ?>




      <div class="container" style="margin-top: 20px;">
        <div class="row">
          
          <div class="col-md-3">
            <div class="card">
                <img class="card-img-top" src="img/veg.png" alt="Card image" style="width:100%">
                
                <div class="card-body">
                  <h4 class="card-title">Veg Items</h4>
                  <p class="card-text">Here you'll find our all veg items</p>
                  <a href="browse_food.php?category=veg" class="btn btn-primary">View Items</a>
                </div>

              </div>
          </div>
          
          <div class="col-md-3">
            <div class="card">
                <img class="card-img-top" src="img/non-veg.jpeg" alt="Card image" style="width:100%">

                <div class="card-body">
                  <h4 class="card-title">Non-Veg Items</h4>
                  <p class="card-text">Here you'll find our non-veg items</p>
                  <a href="browse_food.php?category=non-veg" class="btn btn-primary">View Items</a>
                </div>
            
            </div>
          </div>
          
          <div class="col-md-3">
            <div class="card">
                <img class="card-img-top" src="img/other.jpg" alt="Card image" style="width:100%">
                
                <div class="card-body">
                  <h4 class="card-title">Other Items</h4>
                  <p class="card-text">Here you'll find desserts and beverages</p>
                  <a href="browse_food.php?category=other" class="btn btn-primary">View Items</a>
                </div>
              </div>
          </div>
          
          <div class="col-md-3">
            <div class="card">
                <img class="card-img-top" src="img/ts.jpeg" alt="Card image" style="width:100%">
                
                <div class="card-body">
                  <h4 class="card-title">Today's Special</h4>
                  <p class="card-text">Check out our today's specials</p>
                  <a href="browse_food.php?category=ts" class="btn btn-primary">View Items</a>
                </div>
              </div>
          </div>
        </div>
      </div>

  
      
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