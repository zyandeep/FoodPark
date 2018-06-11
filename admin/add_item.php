<?php 
  
  // Can only access by admin
  session_start();

  if(empty($_SESSION['a_id']) or empty($_SESSION['username'])) {
      header("Location: index.php"); 
  }


  require_once '../include/dbcon.php';
  require_once '../include/app_vars.php';

  if (isset($_POST['submit'])) {

    // Need input validation
    $item = trim($_POST['i_name']);
    $category = $_POST['category'];
    $price = trim($_POST['price']);
    $pic = $_FILES['image']['name'];
    $new_pic = date("Y-m-d h-i-s") . '_' . $pic;

    $type = $_FILES['image']['type'];
    $error = $_FILES['image']['error'];
    $size =  $_FILES['image']['size'];


    // Image Validation
    if (strpos($type,"image") !== false and $error == 0 and $size > 0) {
        $source = $_FILES['image']['tmp_name'];
        $destination = FP_UPLOADPATH . $new_pic;

        // moving the image from 'temp' folder into ../img/item
        if (move_uploaded_file($source, '../'.$destination)) {

            // Prepare an insert statement
            $sql = "INSERT INTO item (i_name, category, price, image) VALUES (?, ?, ?, ?)";

            if ($stmt = mysqli_prepare($link, $sql)) {

                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssis", $item, $category, $price, $destination);

                // Execute the query
                mysqli_stmt_execute($stmt);

                // Everything is OK
                $is_ok = true;

                mysqli_stmt_close($stmt);
                mysqli_close($link);
            }
            else {
                // Redirect user to db error page
            }
        } 
    }
    else {
        $file_error = true;
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

    <title>Admin | Add Item</title>
  </head>



  <body>
    
    <div class="container">
      
      <?php 
        require_once '../include/admin_header.php';
     
        if (!empty($is_ok)): ?>

          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong> Item <?php echo "'$item'"; $item = $price = ""; ?> is added! </strong> 
          </div>

      <?php endif; ?>


      <div class="form-container">
        <div class="row">
             <div class="col-md-3"></div>

             <div class="col-md-6">

               <h3 class="text-center bg-primary text-white"> Add New Item </h3>

               <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data" id="add_item">
                   
                   <div class="form-group">
                     <label for="i_name" class="font-weight-bold">Item Name:</label>
                     <span class="badge badge-pill badge-danger">Required</span>

                     <input type="text" class="form-control" id="i_name" placeholder="Enter item name" name="i_name" required="required" aria-describedby="name-error" value="<?php echo (empty($item)) ? '' : $item ?>">

                     <small id="name-error" class="form-text text-danger"></small>

                   </div>

                   <div class="form-group">
                      <label for="category" class="font-weight-bold">Category: </label>
                      <select class="form-control" id="category" name="category">
                        <option selected="selected" value="veg">Veg</option>
                        <option value="non-veg">Non Veg</option>
                        <option value="other">Other</option>
                      </select>
                    </div>

                   <div class="form-group">
                     <label for="price" class="font-weight-bold">Price:</label>
                     <span class="badge badge-pill badge-danger">Required</span>

                     <input type="text" class="form-control" id="price" placeholder="Enter price" name="price" required="required" aria-describedby="price-error" value="<?php echo (empty($price)) ? '' : $price ?>">

                     <small id="price-error" class="form-text text-danger"></small>
                   </div>
      

                  <div class="form-group">
                    <label for="file" class="font-weight-bold">Choose the item's image to upload: </label>
                    <span class="badge badge-pill badge-danger">Required</span>

                    <input type="file" id="file" name="image" class="form-control-file" required="required" aria-describedby="file-error">

                    <small id="file-error" class="form-text text-danger">
                      <?php if(!empty($file_error)): ?>
                          Select a valid image file
                      <?php endif; ?>
                    </small>
                  </div>
                    
                  <input class="btn btn-primary" type="submit" name="submit" value="Submit">
                  <input class="btn btn-primary" type="reset" value="Reset">

                  
               </form>
             </div>
             
             <div class="col-md-3"></div>
         </div>
      </div>

      <?php 
        require_once '../include/footer.php';
      ?> 

    </div>


    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>

    <script type="text/javascript">

        $(document).ready(function(){
            let errName, errPrice, errImg;
            errName = errPrice = errImg = false;

            //Item Name Validation
            $("input#i_name").on('input',function(){
                 let data = $(this).val().trim();

                if (data.length == 0) {
                    errName = true;
                    $("small#name-error").html("Please enter a food item name");

                    $(this).focus();
                } 
                else {
                    
                    // Check if the food name is already entered
                    $.get("item_script.php", {i_name: data}, function(res){
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


            //Image format validation
            $('input[type="file"]').on('input',function(){

                //Valid image file formats
                // Also needs backend validation
                let img = ["jpg", "jpeg", "png", "bmp", "gif"];

                let data = $(this).val().trim();
                let ext = data.split(".");

                if (img.indexOf(ext[1]) >= 0) {
                    // Found the file etention
                    errImg = false;
                    $("small#file-error").html("");
                }
                else {
                    errImg = true;
                    $("small#file-error").html("Enter a valid image");
                }

            });


            $("form#add_item").submit(function(e) {
                            
                 // If any error exist, don't submit the form
                if (errName || errPrice || errImg) {
                     e.preventDefault();
                } 
            });
        });

    </script>

  </body>
</html>