<?php 
    
    session_start();

    if (empty($_SESSION['a_id']) or empty($_SESSION['username'])) {
        header("Location: index.php"); 
    }
    
    require_once '../include/dbcon.php';

    $sql = "SELECT * FROM customer";
    
    if($result = mysqli_query($link, $sql)) 
    {
          // Fetching all the records
          $rows = mysqli_fetch_all($result);

          mysqli_free_result($result);
          mysqli_close($link);
     } 
     else{
        // Redirect user to db error page
    }   
?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">

    <title>Admin | View Customers</title>
  </head>
  <body>
    
    <div class="container">
      
    <?php require '../include/admin_header.php'; ?> 


    <?php if (!empty($rows)): ?>

       <div class="container">
           <div class="row">

            <div class="col-md-1"> </div>

               <div class="col-md-10">
            
                
                        <h4 class="text-muted mt-3 p-1">
                            All the FoodPark's Registered Customers... 
                        </h4>

                        <div class="table-responsive mt-4">

                          <table class="table table-hover">
                              <thead class="thead-dark">
                                  <tr>
                                      <th>Name</th>
                                      <th>Address</th>
                                      <th>Phone Number</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  
                                  <?php  foreach ($rows as $row): ?>
                                    
                                    <tr>
                                        
                                        <td><strong> <?php echo $row[1]; ?> </strong></td>
                                        <td><strong> <?php echo $row[2]; ?> </strong></td>
                                        <td><strong><?php echo $row[3]; ?> </strong></td>
                                   </tr>
                                   <?php endforeach; ?>
                              </tbody>
                            </table>
                          </div>
                  </div>

                  <div class="col-md-1"> </div>
            </div>
        </div>


        <?php else: ?>

            <h2 class="text-muted text-center m-5 p-5 "> No Customer Information Exist </h2>

        <?php endif; ?> 


      <?php require '../include/footer.php'; ?> 

    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
  </body>
</html>