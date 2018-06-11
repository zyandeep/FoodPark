<?php 
  session_start();

  // Check if it's a valid access
  if (empty($_SESSION['c_id']) or empty($_SESSION['c_name'])) {
    // Invalid access
    header('Location: index.php');
  }

  
  require_once 'include/dbcon.php';

  // So that it can be accessed outside the functiion
  $err_pswd = "";


  function updateRecord($c_id, $name, $ph_no, $address, $password, $c_password, $link){

    global $err_pswd;

    if (empty($password) and empty($c_password)) {
        // Only update name, ph_no and address

        // Prepare an insert statement
        $sql = "UPDATE customer SET name=?, address=?, phone_no=? WHERE c_id=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $name, $address, $ph_no, $c_id);

            mysqli_stmt_execute($stmt);

            header("Location: customer_profile.php?update=true");
        }
        else {
            // DB Error
        }

    }
    elseif (!empty($password) and !empty($c_password)) {
        if ($password === $c_password) {
            // Update name, ph_no, address and password

            // Hash the password
            $password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare an insert statement
            $sql = "UPDATE customer SET name=?, address=?, phone_no=?, password=? WHERE c_id=?";

            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssssi", $name, $address, $ph_no, $password, $c_id);

                mysqli_stmt_execute($stmt);

                mysqli_stmt_close($stmt);
                mysqli_close($link);

                header("Location: customer_profile.php?update=true");
            }
            else {
                // DB Error
            }

        }
        else {
            $err_pswd = "The two passwords didn't match"; 
        }
    }
    else {
        $err_pswd = "The two passwords didn't match";
    }
  } 



  if (isset($_GET['c_id'])){

    $c_id = $_GET['c_id'];

     // Get all of that customer info from DB
    $sql = "SELECT * FROM customer WHERE c_id=$c_id";

    if ($result = mysqli_query($link, $sql)) {
        if(mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);

            $name = $row['name'];
            $ph_no = $row['phone_no'];
            $address = $row['address'];

        }
        else {
            // No customer with that c_id
        } 
    }
    else {
        // DB Error
    }

  }

  elseif (isset($_POST['submit'])) {
    $c_id = $_POST['c_id'];
    $name = trim($_POST['name']);
    $ph_no = trim($_POST['ph_no']);
    $address = trim($_POST['address']);
    $password = trim($_POST['password']);
    $c_password = trim($_POST['c_password']);


    if (empty($name) or empty($ph_no) or empty($address)) {
        $err_req = true;
    }
    else {

        // Verify phone number uniqueness
        $sql="SELECT phone_no FROM customer WHERE c_id=$c_id";
        $result = mysqli_query($link, $sql)
            or die('DB Error');

        $row = mysqli_fetch_assoc($result);

        if ($ph_no != $row['phone_no']) {
            // Customer has changed the phone no
            // Check if the modified phone number already exist

            $sql="SELECT * FROM customer WHERE phone_no='$ph_no'";
            $result = mysqli_query($link, $sql)
                or die('DB Error');

            if(mysqli_num_rows($result) > 0){
                $err_ph_exist = "This phone no. has already been registered";
            }
            else {
                // The modified ph no is unique
                updateRecord($c_id, $name, $ph_no, $address, $password, $c_password, $link);
            }
        }
        else {
            // The customer has not changed the phone number
            updateRecord($c_id, $name, $ph_no, $address, $password, $c_password, $link);
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

    <title>FoodPark | Update Profile</title>
  </head>
  <body>
    
    <div class="container">
      
    <?php require 'include/header.php';?> 


    <?php if(! empty($err_req)): ?>

        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong> Please fill out all the required information </strong>
        </div>

    <?php endif; ?>




     <div class="form-container">
       <div class="row">
            <div class="col-md-3"></div>

            <div class="col-md-6">
              <h3 class="text-center bg-primary text-white"> Update Your Information </h3>

              <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
                  <div class="form-group">
                    <label for="name" class="font-weight-bold">Name:</label>
                    <span class="badge badge-pill badge-danger">Required</span>

                    <input type="text" class="form-control" id="name" placeholder="Enter your name" name="name" required="required" value="<?php echo $name ?>" aria-describedby="name-error">

                    <small id="name-error" class="form-text text-danger">
                    </small> 

                  </div>

                  <div class="form-group">
                    <label for="cph_no" class="font-weight-bold">Phone Number:</label>
                    <span class="badge badge-pill badge-danger">Required</span>

                    <input type="text" class="form-control" id="cph_no" placeholder="Enter your phone no" name="ph_no" required="required" value="<?php echo $ph_no ?>" aria-describedby="phone-error">

                    <small id="phone-error" class="form-text text-danger">
                       <?php echo !empty($err_ph_exist) ? $err_ph_exist : ''; ?>
                    </small> 


                  </div>
                  
                  <div class="form-group">
                    <label for="pw" class="font-weight-bold">New Password:</label>
                    <input type="password" class="form-control" id="pw" placeholder="Enter your password" name="password">
                  </div>

                  <div class="form-group">
                    <label for="cpwd" class="font-weight-bold">Confirm New Password:</label>
                    <input type="password" class="form-control" id="cpwd" placeholder="Confirm password" name="c_password" aria-describedby="pwd-error" value="">
                    
                    <small id="pwd-error" class="form-text text-danger">
                       <?php echo !empty($err_pswd) ? $err_pswd : ''; ?>
                    </small> 
                  </div>

                  <div class="form-group">
                    <label for="addrs" class="font-weight-bold">Address:</label>
                    <span class="badge badge-pill badge-danger">Required</span>

                    <textarea id="addrs" class="form-control" placeholder="Enter your adderess" name="address" required="required" rows="3" aria-describedby="adds-error"><?php echo $address ?></textarea>

                    <small id="adds-error" class="form-text text-danger">
                    </small>
                  </div>

                  <input type="hidden" name="c_id" value="<?php echo $c_id; ?>">
                  <input class="btn btn-primary" type="submit" name="submit" value="Update">
                  <input class="btn btn-primary" type="reset" value="Reset">
              </form>
            </div>
            
            <div class="col-md-3"></div>
        </div>
     </div>     


      
    <?php require 'include/footer.php';?> 

    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script>
        
        $(document).ready(function(){

           let nameRegex = /^[a-zA-Z ]+$/;
           let phRegex = /^\d{10}$/;

           let errName, errPh, errPwd, errAdd;
           errName = errPwd = errPh = errAdd = false;


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

           $("input#cph_no").on('input',function(){
                let data = $(this).val().trim();
                let id = $('input[type="hidden"]').val().trim();


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
                        $.get("cus_ajax.php", {ph_no: data, c_id: id}, function(res){
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

                if (cpw.length != 0 && pw.length != 0) {
                    if(pw != cpw) {
                        errPwd = true;

                        $("small#pwd-error").html("The passwords didn't match");
                        $(this).focus();
                    }
                    else {
                        errPwd = false;
                        $("small#pwd-error").html("");   
                    }
                }
                else if(cpw.length == 0 && pw.length == 0){
                   errPwd = false;
                   $("small#pwd-error").html(""); 
                }
                else {
                    errPwd = true;

                    $("small#pwd-error").html("The passwords didn't match");
                    $(this).focus(); 
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
                
                if (errName || errPh || errPwd || errAdd) {
                    e.preventDefault();
                } 
           });

        });

    </script>

  </body>
</html>