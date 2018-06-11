<?php

    // Regarding bill status:
    // 1 --> Paid
    // 0 --> Unpaid

    session_start();
    
    // Check if it's a valid access
    if (empty($_SESSION['c_id']) or empty($_SESSION['c_name'])) {
      // Invalid access
      header('Location: index.php');
    }



    require_once 'include/dbcon.php';

    // Delete the order 
    if (isset($_POST['cancel_order'])) {
        
        $o_id = $_POST['o_id'];

        // Delete from 'order' tabel
        $sql = "DELETE FROM `order` WHERE o_id=$o_id";

        if(mysqli_query($link, $sql)){

            // Now, delete from order-master tabel
            $sql = "DELETE FROM order_master WHERE o_id=$o_id";
            if (mysqli_query($link, $sql)) {

                // Now, delete from bill tabel
                $sql = "DELETE FROM bill WHERE o_id=$o_id";
                if (mysqli_query($link, $sql)) {
                    // All the records are cleared
                    $c_order = true;
                }
                else {
                    // DB Error
                }
            }
            else {
                // DB Error
            }
        }
        else {
            // DB Error
        }
    }



    if (isset($_GET['search']) and !empty($_GET['bill_date'])) {

        // Need validation 
        $bill_date = $_GET['bill_date'];

        $sql = "SELECT o_id, DATE_FORMAT(date,'%d-%M-%Y') AS date, total_amount, status FROM bill WHERE c_id=$_SESSION[c_id] AND date='$bill_date' ORDER BY date DESC";        
    }
    else {
        $sql = "SELECT o_id, DATE_FORMAT(date,'%d-%M-%Y') AS date, total_amount, status FROM bill WHERE c_id=$_SESSION[c_id] ORDER BY date DESC";
    }

    if ($result = mysqli_query($link, $sql)) {
        if(mysqli_num_rows($result) > 0) {
            $bills = mysqli_fetch_all($result, MYSQLI_ASSOC);

            // Free result set
            mysqli_free_result($result);
        }
        else {
            $no_bill=true;
        }
    }
    else {
        // DB Error
    }


    // Close connection
    mysqli_close($link);
?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">

    <title>Food Park | View Orders</title>
  </head>
  <body>
    
    <div class="container">
      
    <?php require 'include/header.php'; ?> 


    <?php if (isset($c_order) and $c_order === true): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong> Order #<?php echo $o_id; ?> has been canceled </strong>
        </div>
    <?php endif; ?>


    <div class="container">
        <div class="row my-2">
            <div class="col-md-7">

                <?php if (!isset($no_bill)): ?>
                    <h4 class="text-dark mt-2">
                        You have placed the following orders...
                    </h4>
                <?php endif; ?>

            </div>
            <div class="col-md-5">
                <form class="form-inline" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                  <label class="text-dark font-weight-bold" for="date">Search for orders:</label>
                  <input name="bill_date" type="date" class="form-control form-control-lg mx-2" id="date">
                  <button type="submit" name="search" class="btn btn-lg btn-primary">Go</button>
                </form>
            </div>
        </div>
    </div>


    <?php if (isset($no_bill) and $no_bill === true): ?>
        <h2 class="text-muted text-center py-2 my-5">
            
            <?php if(isset($bill_date)): ?>
                No order on <?php echo $bill_date; ?>

            <?php else: ?>
                 No order has been placed yet

            <?php endif; ?>


        </h2>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-sm my-3">
             <thead class="thead-dark">
                   <tr>
                     <th>Order ID</th>
                     <th>Date</th>
                     <th>Total Amount</th>
                     <th>Remark</th>
                   </tr>
              </thead>
               <tbody>
                    <?php foreach ($bills as $bill): ?>
                        <tr>
                            <td>
                                <a href="generate_bill.php?order_id=<?php echo $bill['o_id']; ?>" data-toggle="tooltip" data-placement="right" title="View the bill">Order #: <?php echo $bill['o_id']; ?></a>
                            </td>

                            <td> <?php echo $bill['date']; ?> </td>
                            <td> Rs. <?php echo number_format($bill['total_amount']); ?> </td>
                            <td>
                                <?php if($bill['status'] == 0): ?>
                                    <!-- <strong class="text-warning"> Unpaid </strong> -->

                                    <form class="form-inline cancel" action="view_bills.php" method="POST">
                                        <input type="hidden" name="o_id" value="<?php echo $bill['o_id']; ?>">
                                        <button type="submit" name="cancel_order" class="btn btn-danger btn-sm">Cancel Order</button>
                                    </form>

                                <?php else: ?>
                                    <strong class="text-success"> Paid </strong>

                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>    
              </tbody>  
            </table>
        </div>

    <?php endif; ?>


   
      
    <?php require 'include/footer.php'; ?> 

    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/validate.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>



  </body>
</html>