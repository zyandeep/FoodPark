<?php

    // Regarding bill status:
    // 1 --> Paid
    // 0 --> Unpaid

    session_start();
    
    // Check if it's a valid access
    if (empty($_SESSION['a_id']) or empty($_SESSION['username'])) {
      // Invalid access
      header('Location: index.php');
    }



    require_once '../include/dbcon.php';

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


    // Approve the order 
    elseif (isset($_POST['app_order'])) {
        
        $o_id = $_POST['o_id'];

        // Delete from 'order' tabel
        $sql = "UPDATE bill SET status=1 WHERE o_id=$o_id";

        if(mysqli_query($link, $sql)){
            $a_order = true;
        }
        else {
            // DB Error
        }
    }


    if (isset($_GET['search']) and !empty($_GET['bill_date'])) {

        // Need validation 
        $bill_date = $_GET['bill_date'];

        $sql = "SELECT o_id, DATE_FORMAT(date,'%d-%M-%Y') AS date, total_amount, status FROM bill WHERE date='$bill_date' ORDER BY date DESC";        
    }
    else {
        $sql = "SELECT o_id, DATE_FORMAT(date,'%d-%M-%Y') AS date, total_amount, status FROM bill ORDER BY date DESC";
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

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">

    <title>Admin | View Orders</title>
  </head>
  <body>
    
    <div class="container">
      
    <?php require '../include/admin_header.php'; ?> 


    <?php if (isset($c_order) and $c_order === true): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong> Order #<?php echo $o_id; ?> has been canceled </strong>
        </div>
    <?php endif; ?>

    <?php if (isset($a_order) and $a_order === true): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong> Order #<?php echo $o_id; ?> has been approved </strong>
        </div>
    <?php endif; ?>


    <div class="container">
        <div class="row my-2">
            <div class="col-md-7">

                <?php if (!isset($no_bill)): ?>
                    <h4 class="text-dark mt-2 text-muted">
                        The following orders have been placed...
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

                            <?php if($bill['status'] == 0): ?>
                                
                                <td>
                                    <form class="form-inline cancel" action="view_bills.php" method="POST">
                                        <input type="hidden" name="o_id" value="<?php echo $bill['o_id']; ?>">
                                        <button type="submit" name="cancel_order" class="btn btn-danger btn-sm mb-2">Cancel &nbsp;</button>
                                    </form>

                                    <form class="form-inline" action="view_bills.php" method="POST">
                                        <input type="hidden" name="o_id" value="<?php echo $bill['o_id']; ?>">
                                        <button type="submit" name="app_order" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                </td>

                            <?php else: ?>
                                    <td>
                                        <strong class="text-success"> Paid </strong>
                                    </td>

                            <?php endif; ?>

                        </tr>
                    <?php endforeach; ?>    
              </tbody>  
            </table>
        </div>

    <?php endif; ?>


   
      
    <?php require '../include/footer.php'; ?> 

    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../js/jquery.js"></script>
    <script src="../js/popper.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
    <script src="../js/validate.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

  </body>
</html>