<?php

    if (empty($im_id) and empty($_POST['im_id'])) {
        // Invalid access
        header("Location: index.php");
    }

    if (isset($_POST['submit'])) {

        // Need input validation
        $im_id = $_POST['im_id'];
        $item = trim($_POST['i_name']);
        $category = $_POST['category'];
        $price = trim($_POST['price']);

        if (isset($_POST['menu'])) {
            $status = 1;
        }
        else {
            $status = 0;
        }

        require_once '../include/dbcon.php';

        $sql = "UPDATE item SET i_name=?, category=?, price=?, status=? WHERE im_id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {

            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssiii", $item, $category, $price, $status, $im_id);

            // Execute the query
            mysqli_stmt_execute($stmt);

            header("Location: view_food.php?update=$item&category=$category");
        }
        else {
            // DB Error
        }
    }

    // Get all the info about the item
    $sql = "SELECT * FROM item WHERE im_id=$im_id";

    if($result = mysqli_query($link, $sql)){
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        }
    } 
    else{
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

    <title>Admin | Update Item</title>
  </head>
  <body>
    
    <div class="container">
      
    <?php require '../include/admin_header.php';?> 

      
    <div class="container">
        <div class="row mt-5">
            <div class="col-1"></div>

            <div class="col-3">
                <div class="card">
                  <img class="card-img-top" src="../<?php echo $row['image']; ?>" alt="Card image cap">  
                  
                  <div class="card-body">
                    <h4 class="card-title"> <?php echo $row['i_name']; ?> </h4>
                    <p class="card-text">
                        Update information about the food item
                    </p>
                  </div>

                </div>
            </div>

            <div class="col-6">

                <form method="POST" action="update_item.php" id="update_item">

                  <div class="form-group">
                    <label for="i_name" class="font-weight-bold">Item Name:</label>
                    <span class="badge badge-pill badge-danger">Required</span>

                    <input type="text" class="form-control" id="i_name" name="i_name" required="required" value="<?php echo $row['i_name']; ?>" aria-describedby="name-error">

                    <small id="name-error" class="form-text text-danger"></small>
                  </div>

                  <div class="form-group">

                     <label for="category" class="font-weight-bold">Category: </label>
                     <span class="badge badge-pill badge-danger">Required</span>

                     <select class="form-control" id="category" name="category" required="required">

                      <?php if($row['category'] == "veg"): ?>
                            <option value="veg" selected="selected">Veg</option>
                            <option value="non-veg">Non Veg</option>
                            <option value="other">Other</option>

                      <?php elseif($row['category'] == "non-veg"): ?>
                            <option value="veg">Veg</option>
                            <option value="non-veg" selected="selected">Non Veg</option>
                            <option value="other">Other</option>
                       
                      <?php else: ?>
                            <option value="veg">Veg</option>
                            <option value="non-veg">Non Veg</option>
                            <option value="other" selected="selected">Other</option>

                      <?php endif; ?>
                     
                     </select>

                   </div>

                  <div class="form-group">
                    <label for="price" class="font-weight-bold"> Price: </label>
                    <span class="badge badge-pill badge-danger">Required</span>

                    <input type="text" class="form-control" name="price" id="price" value="<?php echo $row['price']; ?>" required="required" aria-describedby="price-error">

                    <small id="price-error" class="form-text text-danger"></small>
                  </div>

                  <div class="form-group">
                    <label class="font-weight-bold"> Put it on the FoodPark's menu? &nbsp; </label>
                    <input type="checkbox" name="menu" <?php echo ($row['status'] == 1)? "checked" : '' ;?> >

                  </div>

                  <input type="hidden" name="im_id" value="<?php echo $im_id; ?>">

                  <button type="submit" name="submit" class="btn btn-primary">Update</button>
                  <button type="reset" class="btn btn-primary">Reset</button>

                </form>

            </div>

            <div class="col-2"></div>
        </div>
    </div>

      
    <?php require '../include/footer.php';?> 

    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../js/jquery.js"></script>
    <script src="../js/popper.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>

    <script type="text/javascript">

        $(document).ready(function(){
            let errName, errPrice;
            errName = errPrice = false;

            //Item Name Validation
            $("input#i_name").on('input',function(){
                 let data = $(this).val().trim();
                 let id = $('input[type="hidden"]').val().trim();

                if (data.length == 0) {
                    errName = true;
                    $("small#name-error").html("Please enter a food item name");

                    $(this).focus();
                } 
                else {
                    
                    // Check if the food name is already entered
                    $.get("item_script.php", {i_name: data, im_id: id}, function(res){
                        if (res) {
                            errName = true;
                            $("small#name-error").html(res);

                            $(this).focus();
                        } 
                        else {
                            errName = false;
                            $("small#name-error").html("");
                        }
                    });
                }

            });


            //Item Price Validation
            $("input#price").on('input',function(){

                let pattern = /^\d{1,3}$/;
                let data = $(this).val().trim();

                if (data.length == 0) {
                    errPrice = true;
                    $("small#price-error").html("Please enter the price");

                    $(this).focus();
                } 
                else if(pattern.test(data) && parseInt(data) > 0) {
                    errPrice = false;
                    $("small#price-error").html("");
                }
                else {
                    errPrice = true;
                    $("small#price-error").html("Enter a valid price");

                    $(this).focus();
                }

            });


            $("form#update_item").submit(function(e) {
                            
                 // If any error exist, don't submit the form
                if (errName || errPrice ) {
                     e.preventDefault();
                } 
            });
        });

    </script>

  </body>
</html>