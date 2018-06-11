<?php

  session_start();

  // Check if it's a valid access
  if (empty($_SESSION['a_id']) or empty($_SESSION['username'])) {
    // Invalid access
     header('Location: index.php');
  }


  if (isset($_GET['order_id'])) {
      $order_id = $_GET['order_id'];

      require_once '../include/dbcon.php';

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

    <title>  Admin | Bill Generation </title>
  </head>
  <body>
    
    <div class="container">
      
    <?php require '../include/admin_header.php'; ?> 


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


      
    <?php require '../include/footer.php'; ?> 

    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../js/jquery.js"></script>
    <script src="../js/popper.js"></script>
    <script src="../js/bootstrap.min.js"></script>
     <script src="../js/main.js"></script>

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