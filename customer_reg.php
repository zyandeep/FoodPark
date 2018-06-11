<?php 
  // Starting session
  session_start();

  // Check if it's a new customer
  if (isset($_SESSION['c_id']) or isset($_SESSION['c_name'])) {
    // Invalid access
    header('Location: index.php');
  }

  
  require_once 'include/dbcon.php';


  if (isset($_POST['submit'])) {

    $name = trim($_POST['name']);
    $ph_no = mysqli_real_escape_string($link, trim($_POST['ph_no']));
    $password = trim($_POST['password']);
    $c_password = trim($_POST['c_password']);
    $address = trim($_POST['address']);


    // If any of the mandatory filed is empty, then flag error message
    if (empty($name) or empty($ph_no) or empty($password) or empty($c_password) or empty($address)) {
        $err_empty = true;
    }
    else 
    {

        // Check if the phone number already exist
        $sql="SELECT * FROM customer WHERE phone_no='$ph_no'";

        $result = mysqli_query($link, $sql)
            or die('DB Error');

        if(mysqli_num_rows($result) === 0){
            if ($password !== $c_password) {
                $err_pswd = "The passwords didn't match";
            }
            else {
                // Hash the password
                $password = password_hash($password, PASSWORD_DEFAULT);

                // Prepare an insert statement's template
                $sql = "INSERT INTO customer (name, address, phone_no, password) VALUES (?, ?, ?, ?)";

                if ($stmt = mysqli_prepare($link, $sql)) {

                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "ssss", $name, $address, $ph_no, $password);

                    // Execute the query
                    mysqli_stmt_execute($stmt);

                    mysqli_stmt_close($stmt);
                    mysqli_close($link);

                    header("Location: index.php?cus_reg=true");
                }
                else{
                    // DB Error
                }
            }
        }
        else {
            $err_ph_exist = "This phone no. has already been registered";
        }
        
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


    <title>FoodPark | Customer Registration</title>
  </head>


  <body>
    
    <div class="container">
      
      <?php require 'include/header.php'; ?>

      <?php if(! empty($err_empty)): ?>

          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Please fill out all the required information</strong>
          </div>

      <?php endif; ?>

 
       
      <div class="form-container">
        <div class="row">
             <div class="col-md-3"></div>

             <div class="col-md-6">
               <h3 class="text-center bg-primary text-white"> Customer Registration </h3>

               <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" id="cus_reg">

                   <div class="form-group">
                     <label for="name" class="font-weight-bold">Name:</label>
                     <span class="badge badge-pill badge-danger">Required</span>

                     <input type="text" class="form-control" id="name" placeholder="Enter your name" name="name" required="required" value="<?php echo empty($name)? '' : $name; ?>" aria-describedby="name-error">

                     <small id="name-error" class="form-text text-danger"></small>

                   </div>
                   
                   <div class="form-group">
                     <label for="cph_no" class="font-weight-bold">Phone Number:</label>
                     <span class="badge badge-pill badge-danger">Required</span>

                     <input type="text" class="form-control" id="cph_no" placeholder="Enter your phone no" name="ph_no" required="required" value="<?php echo empty($ph_no)? '' : $ph_no; ?>" aria-describedby="phone-error">

                     <small id="phone-error" class="form-text text-danger">
                        <?php echo !empty($err_ph_exist) ? $err_ph_exist : ''; ?>
                     </small> 
                   </div>

                   <div class="form-group">
                     <label for="pw" class="font-weight-bold">Password:</label>
                     <span class="badge badge-pill badge-danger">Required</span>

                     <input type="password" class="form-control" id="pw" placeholder="Enter your password" name="password" required="required">
                   </div>

                   <div class="form-group">
                     <label for="cpwd" class="font-weight-bold">Confirm Password:</label>
                     <span class="badge badge-pill badge-danger">Required</span>

                     <input type="password" class="form-control" id="cpwd" placeholder="Confirm password" name="c_password" required="required" aria-describedby="pwd-error">
                     
                     <small id="pwd-error" class="form-text text-danger">
                        <?php echo !empty($err_pswd) ? $err_pswd : ''; ?>
                     </small> 
                   </div>

                   <div class="form-group">
                     <label for="addrs" class="font-weight-bold">Address:</label>
                     <span class="badge badge-pill badge-danger">Required</span>

                     <textarea id="addrs" class="form-control" placeholder="Enter your adderess" name="address" required="required" rows="3" aria-describedby="adds-error"><?php echo empty($address)? '' : $address; ?></textarea>


                    <small id="adds-error" class="form-text text-danger"></small> 

                   </div>

                   <input class="btn btn-primary" type="submit" name="submit" value="Submit">
                   <input class="btn btn-primary" type="reset" value="Reset">
               </form>
             </div>
             
             <div class="col-md-3"></div>
         </div>
      </div>

      <?php 
        require_once 'include/footer.php';
      ?> 

    </div>


    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>


    <script>
        
        $(document).ready(function(){

           let nameRegex = /^[a-zA-Z ]+$/;
           let phRegex = /^\d{10}$/;

           let errName, errPh, errPwd, errAdd;
           errName = errPwd = errPh = errAdd = false;


           // For Name validation
           $("input#name").on('input',function(){
                let data = $(this).val().trim();

                if (data.length == 0) {
                    errName = true;
                    $("small#name-error").html("Please enter a name");

                    $(this).focus();
                } 
                else {
                    if (nameRegex.test(data) == false) {
                        errName = true;
                        $("small#name-error").html("Only alphabates and white space are allowed");

                        $(this).focus();
                    }
                    else {
                        errName = false;
                        $("small#name-error").html("");
                    }
                }

           });

           // For Phone Number validation
           $("input#cph_no").on('input',function(){
                let data = $(this).val().trim();

                if (data.length == 0) {
                    errPh = true;
                    $("small#phone-error").html("Please Enter a phone no");

                    $(this).focus();
                } 
                else {
                    if (phRegex.test(data) == false) {
                        errPh = true;
                        $("small#phone-error").html("Phone no must be of 10 digits");

                        $(this).focus();
                    }
                    else {

                        // Check if the phone no is already registered
                        $.get("cus_ajax.php", {ph_no: data}, function(res){
                            if (res) {
                                errPh = true;
                                $("small#phone-error").html(res);

                                $(this).focus();
                            } 
                            else {
                                errPh = false;
                                $("small#phone-error").html("");
                            }
                        });
                    }
                }
           });


           $("input#cpwd").blur(function(){
                let cpw = $(this).val().trim();
                let pw = $("input#pw").val().trim();

                if (cpw.length == 0 || pw.length == 0) {
                    errPwd = true;

                    $("small#pwd-error").html("The passwords are empty");
                    $(this).focus();

                }
                else if(pw != cpw) {
                    errPwd = true;

                    $("small#pwd-error").html("The passwords didn't match");
                    $(this).focus();
                }
                else {
                    errPwd = false;
                    $("small#pwd-error").html("");
                } 

           });


           $("textarea").on('blur',function(){
                let data = $(this).val().trim();

                if (data.length == 0) {
                    errAdd = true;
                    $("small#adds-error").html("Please enter the address");

                    $(this).focus();
                } 
                else {
                    errAdd = false;
                    $("small#adds-error").html("");
                }

           });


           $("form#cus_reg").submit(function(e) {
                           
                // If any error exist, don't submit the form
                if (errName || errPh || errPwd || errAdd) {
                    e.preventDefault();
                } 
           });

        });

    </script>

  </body>
</html>