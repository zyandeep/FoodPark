<?php 
    
    session_start();

    if (empty($_SESSION['a_id']) or empty($_SESSION['username'])) {
        header("Location: index.php"); 
    }    


    require_once '../include/dbcon.php';

    

    if (isset($_GET['category'])) {

        date_default_timezone_set("Asia/Kolkata");
        $dat = date("Y-m-d");

        $category = $_GET['category'];

       if ($category == "veg") {
          $sql = "SELECT * FROM item WHERE category='veg' AND im_id NOT IN (SELECT im_id FROM todays_special WHERE date='$dat')";
       }
       elseif ($category == "non-veg") {
          $sql = "SELECT * FROM item WHERE category='non-veg' AND im_id NOT IN (SELECT im_id FROM todays_special WHERE date='$dat')";   
       }
       elseif ($category == "other") {
          $sql = "SELECT * FROM item WHERE category='other' AND im_id NOT IN (SELECT im_id FROM todays_special WHERE date='$dat')";
       }
       elseif ($category == "ts") {
          $sql = "SELECT item.im_id, i_name, category, price, image, todays_special.t_id FROM item, todays_special WHERE item.im_id=todays_special.im_id AND date='$dat' AND item.status=1";
       }


       if(isset($sql) and $result = mysqli_query($link, $sql)) {

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

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">

    <title>Admin | View Food Items</title>
  </head>
  <body>
    
    <div class="container">
          
        <?php require '../include/admin_header.php'; ?>
       
        <?php if(!empty($_GET['add'])): ?>

          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong> Item <?php echo "'$_GET[add]' "; ?> added to today's special </strong>
          </div>

        <?php elseif(!empty($_GET['remove'])): ?>

            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong> Item <?php echo "'$_GET[remove]' "; ?> removed from today's special </strong>
            </div>

        <?php elseif(!empty($_GET['update'])): ?>

            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong> Item <?php echo "'$_GET[update]' "; ?> has been updated </strong>
            </div>

        <?php endif; ?>


        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <?php if (!empty($rows)): ?>

                         <h4 class="text-muted mt-3 p-1">
                            Food items for category "<?php echo ($category=='ts')? "today's special" : $category;?>"...
                             
                         </h4>

                        <div class="table-responsive mt-4">

                          <table class="table table-hover">
                              <thead class="thead-dark">
                                  <tr>
                                     <th>Image</th>
                                      <th>Item</th>
                                      <th>Price</th>
                                      <th class="text-center">Action</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  
                                  <?php  foreach ($rows as $row): ?>
                                    
                                    <tr>
                                        
                                        <td> <?php echo '<img class="rounded" src="../' . "$row[4]".'">';?></td>
                                        <td><strong> <?php echo $row[1]; ?> </strong></td>
                                        <td><strong>Rs. <?php echo number_format($row[3]); ?> </strong></td>

                                        <form  class="form-inline" method="POST" action="add_todayspecial.php">
                                            <input type="hidden" name="im_id" value="<?php echo $row[0];?>">
                                            <input type="hidden" name="category" value="<?php echo $category; ?>">
                                            <input type="hidden" name="i_name" value="<?php echo $row[1]; ?>">


                                        <?php if( $category !== "ts" ): ?>

                                            <?php if($row[5] == 1): ?>

                                                <td style="text-align: center;">
                                                  <button class="btn btn-primary mr-2" type="submit" name="add-ts">Add Today's Special</button>
                                                  <button class="btn btn-info mr-2" type="submit" name="update">Update</button>
                                                </td>

                                            <?php else: ?>

                                                <td style="text-align: center;">
                                                    <span class="text-muted mr-2">Not selling it currently</span>
                                                    <button class="btn btn-info mr-2" type="submit" name="update">Update</button>
                                                </td>

                                            <?php endif; ?>

                                        <?php else: ?>
                                            <input type="hidden" name="t_id" value="<?php echo $row[5]; ?>">

                                            <td style="text-align: center;">
                                              <button class="btn btn-danger" type="submit" name="remove-ts">Remove</button>
                                            </td>

                                        <?php endif; ?>
                  
                                             
                                        </form>
                                         
                                    </tr>

                                   <?php endforeach; ?>
                              </tbody>
                            </table>
                          </div>

                    <?php else: ?>
                        <h2 class="text-muted text-center m-5 p-5 "> No item for this category</h2>

                    <?php endif; ?> 
  
                  </div>
            </div>
        </div>

        

      
      <?php require '../include/footer.php'; ?> 

    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
  </body>
</html>