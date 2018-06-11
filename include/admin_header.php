<style>
  .jumbotron {
    background-image: url("../img/bg.jpg");
    margin-bottom: 0;
    background-repeat: no-repeat;
  }
</style>

 
<div class="jumbotron jumbotron-fluid">
 <div class="container">
   <h1 class="display-2 text-light">Food Park</h1>
   <h3 class="text-light">Hello! Welcome to Food Park.</h3>
 </div>
</div>


  <nav class="navbar sticky-top navbar-expand-md navbar-dark bg-dark">
    
    <?php if(isset($_SESSION['a_id'])): ?>
        <a class="navbar-brand" href="admin_home.php">
    <?php else: ?>
        <a class="navbar-brand" href="index.php">

    <?php endif; ?>
    
        <img src="../img/logo.jpg" alt="Logo" style="width:40px;" class="img-thumbnail">
    </a>
  
  
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
  
    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
      <ul class="navbar-nav mr-auto mt-2 mt-md-0">
        
        <li class="nav-item">
          
          <?php if(isset($_SESSION['a_id'])): ?>
              <a class="nav-link" href="admin_home.php">Home</a>

          <?php else: ?>
             <a class="nav-link" href="index.php">Home</a>
          <?php endif; ?>

        </li>


        <?php if(isset($_SESSION['a_id'])): ?>
          
          <li class="nav-item">
            <a class="nav-link" href="view_bills.php" id="msg"></a>
          </li>
         
         <?php endif; ?>


         <?php if(isset($_SESSION['a_id'])): ?>
           
           <li class="nav-item">
             <li class="nav-item">
             <a class="nav-link" href="admin_auth.php?logout=true">Log out</a>
             </li>
          </li>

          <?php endif; ?>
  
      </ul>

      <?php if(isset($_SESSION['a_id'])): ?> 

            <span class="navbar-text">
                <h5 class="font-weight-bold text-capitalize">Welcome <?php echo $_SESSION['username']; ?></h5>
            </span>

      <?php endif; ?>

    </div>
  </nav>