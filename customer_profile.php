<?php 
  session_start(); 

  // Check if it's a valid access
  if (empty($_SESSION['c_id']) or empty($_SESSION['c_name'])) {
    // Invalid access
    header('Location: index.php');
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

    <title> FoodPark | Customer Profile </title>
  </head>
  <body>
    
    <div class="container">
      
    <?php require 'include/header.php';?> 

    <?php if(! empty($_GET['update'])): ?>

        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>Your account informaton has been updated successfully</strong>
        </div>

    <?php endif; ?>




    <div class="container">
      <div class="row mt-4 mb-4">
        
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <div class="card bg-light text-dark">
              <img class="card-img-top" src="img/update_profile.jpg">
               <div class="card-body">
                <h4 class="card-title">My Profile</h4>
                <p class="card-text">Here you can update your information</p>
                 <a href="update_profile.php?c_id=<?php echo $_SESSION['c_id'];?>" class="btn btn-primary">Update Profile</a>
               </div>
             </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light text-dark">
              <img class="card-img-top" src="img/order3.png">
               <div class="card-body">
                    <h4 class="card-title">My Orders</h4>
                    <p class="card-text">Here you can review your orders</p>
                    <a href="view_bills.php" class="btn btn-primary">View Orders</a>
               </div>
             </div>
        </div>
        <div class="col-md-3"></div>
       
      </div>
    </div>


    <?php require 'include/footer.php'; ?> 

    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>