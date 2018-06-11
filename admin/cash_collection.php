<?php  
    session_start(); 

    if(empty($_SESSION['a_id']) or empty($_SESSION['username'])) {
        header("Location: index.php"); 
    }

    require_once '../include/dbcon.php';


    if (isset($_POST['submit'])) {
        // Show data for the given date 
        $dat = $_POST['date'];

        if (empty($dat)) {
            $err_date = true;
        }
    }
    
    elseif (isset($_GET['date'])) {
        // Show detail sale for the given date
        $dat = $_GET['date'];

        $sql = "SELECT item.im_id, i_name, category, price, sum(quantity) as quantity, price * sum(quantity) as total FROM item, order_master WHERE item.im_id = order_master.im_id AND date='$dat'GROUP BY order_master.im_id";

        if($result = mysqli_query($link, $sql)){
            // Fetch all
            $rows = mysqli_fetch_all($result,MYSQLI_ASSOC);
        }
        else {
            // DB Error
        }

    }

    else {
        // Today's date
        date_default_timezone_set("Asia/Kolkata");
        $dat = date("Y-m-d");
    }


    $sql = "SELECT sum(total_amount) AS cash FROM bill WHERE date='$dat'";

    if($result = mysqli_query($link, $sql)){
        $row = mysqli_fetch_assoc($result);
    }
    else {
        // DB Error
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

    <title>Admin | Day-wise Cash Collection</title>
  </head>
  <body>
    
    <div class="container">
      
        <?php require '../include/admin_header.php';?> 



        <?php if ( !empty($err_date) ): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Please enter a valid date</strong>
            </div>
        <?php endif; ?>


         <div class="container">
          <div class="row mt-4">
            <div class="col-md-2"></div>
            <div class="col-md-2"></div>

            <div class="col-md-8">
                
               <form  class="form-inline" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                   
                   <div class="form-group">
                       <label class="text-dark font-weight-bold" for="date">Enter Date: </label>
                       <input class="form-control form-control-lg mx-2" type="date" name="date" id="date" value="<?php echo (empty($dat))? '' : $dat; ?>">    
                   </div>
                   
                   <button type="submit" name="submit" class="btn btn-lg btn-primary">Show</button>

               </form>

            </div>

           
          </div>
        </div> 


        <div class="mt-5 p-3">

            <?php if(empty($err_date)): ?>

                <h3 class="text-center text-muted font-weight-bold mb-4">
                    Cash collection on <?php echo $dat . " Rs. " . number_format($row['cash']); ?>
                </h3>

                <?php if($row['cash'] != null and !isset($rows)): ?>
                    
                    <p class="text-center font-weight-bold mb-4">
                         <a href="cash_collection.php?date=<?php echo $dat; ?>">View Details</a>
                    </p>

                <?php endif; ?>

                <?php if (! empty($rows)): ?>

                    <div class="table-responsive mt-4">

                         <table class="table table-striped table-sm mt-3">
                             <thead class="thead-dark">
                                 <th>Name</th>
                                 <th>Category</th>
                                 <th>Price Per Item</th>
                                 <th>Quantity Sold</th>
                                 <th>Amount</th>
                             </thead>
                             <tbody>
                                 <?php foreach ($rows as $item):?>
                                     <tr>
                                         <td><?php echo $item['i_name']; ?></td>
                                         <td><?php echo $item['category']; ?></td>
                                         <td>Rs. <?php echo number_format($item['price']); ?></td>
                                         <td><?php echo $item['quantity']; ?></td>
                                         <td>Rs. <?php echo number_format($item['total']); ?></td>
                                     </tr>
                                 <?php endforeach; ?>
                             </tbody>
                         </table>

                         <h5 class="text-right pr-4">Total amount = Rs. <?php echo number_format($row['cash']); ?> </h5>
                          
                    </div>

                 <?php endif; ?>

            <?php endif; ?>
           
       </div>


        <?php require '../include/footer.php';?> 

    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../js/jquery.js"></script>
    <script src="../js/popper.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
  </body>
</html>