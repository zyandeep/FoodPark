<?php

  require_once 'include/order_class.php';
  require_once 'include/dbcon.php';

  session_start();

  // Check if it's a valid access
  if (empty($_SESSION['c_id']) or empty($_SESSION['c_name'])) {
    // Invalid access
    header('Location: index.php');
  }



  if (isset($_POST['yes'])) {

    // Create a unique order id for this session
    $sql = "INSERT INTO `order` (c_id) VALUES ($_SESSION[c_id])";

    if(mysqli_query($link, $sql)){
        $order_id = mysqli_insert_id($link);

        date_default_timezone_set("Asia/Kolkata");
        $dat = date("Y-m-d");

        // Now, insert data into order_master
        // Prepare an insert statement
        $sql = "INSERT INTO order_master(o_id, im_id, quantity, date) VALUES (?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){

            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iiis", $order_id, $im_id, $quantity, $dat);

            foreach ($_SESSION['orders'] as $key => $order) {
                $im_id = $order->getID();
                $quantity = $order->getQuantity();

                mysqli_stmt_execute($stmt);
            }
            
            // Close statement
            mysqli_stmt_close($stmt);


            // Next, insert data into bill
            $sql = "INSERT INTO bill (o_id, c_id, total_amount, date) VALUES ($order_id, $_SESSION[c_id], $_SESSION[total_amount], '$dat')";

            if(mysqli_query($link, $sql)) {
                $placed_order = true;

                // Remove the orders from the session
                unset($_SESSION['orders']);
                unset($_SESSION['total_amount']);
            } 
            else {
                // DB Error
            }

        } 
        else{
            // DB Error
        }
    }
    else{
        // DB Error
    }
  }

  elseif (isset($_POST['no'])) {

    // Removing the orders from the session
    if(isset($_SESSION['orders'])){
        unset($_SESSION['orders']);
        unset($_SESSION['total_amount']);

        header("Location: index.php?order=cancel");
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

    <title>  FoodPark | Bill Generation </title>
  </head>
  <body>
    
    <div class="container">
      
    <?php require 'include/header.php'; ?> 


    <?php if ( !empty($placed_order)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Your order has been placed successfully!</strong> And will get delivered at your doorstep shortly
        </div>
    <?php endif; ?>

    
    <?php 

        if (isset($_GET['order_id'])) {
            $order_id = $_GET['order_id'];
        }
        
        // Get the customer info for that order id
        $sql = "SELECT * FROM customer, `order` where customer.c_id=`order`.c_id AND o_id=$order_id";

        if ($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) === 1) {
                $cust_row = mysqli_fetch_assoc($result);

                // Get the items the customer ordered
                $sql = "SELECT item.i_name, item.price, order_master.quantity, DATE_FORMAT(order_master.date, '%d-%m-%Y') AS date, item.price * order_master.quantity AS total FROM item, order_master WHERE item.im_id = order_master.im_id and order_master.o_id=$order_id";

                if ($result = mysqli_query($link, $sql)) {
                    $item_rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
                }
                else {
                    // DB Error
                }
            }
        }
        else {
            //DB Error
        }
     ?>

     <div class="container">
        <div class="row mt-1">
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col">
                <button type="button" class="btn btn-primary btn-lg">Print this bill</button>
            </div>
          </div>
     </div>




     <div class="border border-primary rounded m-3 p-3" id="bill">
        <h3 class="text-center text-muted font-weight-bold mb-4">
            <u>Invoice : Food Park</u>
        </h3>

        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <table class="table-sm">
                      <tbody>
                          <tr>
                              <td><span class="text-muted font-weight-bold">Name:</span></td>
                              <td><?php echo $cust_row['name']; ?></td>
                          </tr>
                          <tr>
                              <td><span class="text-muted font-weight-bold">Phone No:</span></td>
                              <td><?php echo $cust_row['phone_no']; ?></td>
                          </tr>
                          <tr>
                              <td><span class="text-muted font-weight-bold">Address:</span></td>
                              <td> <?php echo $cust_row['address']; ?></td>
                          </tr>
                      </tbody>
                    </table>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-3">
                    <table class="table-sm">
                      <tbody>
                          <tr>
                              <td><span class="text-muted font-weight-bold">Order ID:</span></td>
                              <td> <?php echo $order_id; ?></td>
                          </tr>
                          <tr>
                              <td><span class="text-muted font-weight-bold">Bill Date:</span></td>
                              <td> <?php echo $item_rows[0]['date'] ?></td>
                          </tr>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>


         <div class="table-responsive mt-4">
            <table class="table table-striped table-sm">
                <thead class="thead-dark">
                  <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>

                    <?php $total_amount = 0; ?>

                    <?php foreach ($item_rows as $item): ?>

                    <tr>
                        <td> <?php echo $item['i_name']; ?></td>
                        <td> Rs. <?php echo number_format($item['price']); ?> </td>
                        <td> <?php echo $item['quantity']; ?> </td>
                        <td> 
                            <?php 
                                echo $item['total'];
                               $total_amount += $item['total'];
                            ?> 
                        </td>
                    </tr>    

                    <?php endforeach; ?>

                </tbody>
              </table>
        </div> 


        <h5 class="text-right p-2 mt-1">Total bill amount = Rs.<?php echo number_format($total_amount); ?> </h5>
     </div>


      
    <?php require 'include/footer.php'; ?> 

    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>


    <script type="text/javascript">
        $(document).ready(function(){
            
            $("button").click(function(){

                var data = $("#bill").html();

                var mywindow = window.open("", "", 'height=500,width=800');
                
                mywindow.document.write("<!doctype html>");
                mywindow.document.write('<html lang="en"><head><title>FoodPark | Print Invoice</title>');
                mywindow.document.write("<meta charset='utf-8'>");

                mywindow.document.write("<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>");

                mywindow.document.write("<link rel='stylesheet' href='css/bootstrap.min.css'>");
                mywindow.document.write('</head><body >');
                

                mywindow.document.write("<div class='container'>");
                mywindow.document.write(data);
                mywindow.document.write("</div>");

                mywindow.document.write('</body></html>');

                mywindow.print();
                mywindow.close();

            });
        });    
    </script>


  </body>
</html>