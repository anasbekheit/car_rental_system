<?php
//TODO: FIX NAMING CONVENTIONS HTML
//TODO: STYLING IN CSS
require 'server.php';

  if(!isset($_SESSION['user'])){
      header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	unset($_SESSION['user']);
  	header("location: login.php");
  }
  if(isset($_POST['rent_car']))
  {
	  header("location: search.php");
  }
  if(isset($_POST['view_reservations']))
  {
	  header("location: viewReservation.php");
  }

?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="css\index-styling.css">
</head>
<body>

<div class="header">
	<div class="topbar">
		<a href="index.php"><img class="logo" src="css/logo/colored/logo-white.svg"></a>
   		 <!-- logged in user information -->
    	<?php  if (isset($_SESSION['user'])) : ?>
		<div class="welcome">
			<h3><strong><?php echo $_SESSION['user']['fname']; ?></strong></h3>
			<h3> <a class="logoutText" href="index.php?logout='1'">Log out</a> </h3>
		</div>
		<?php endif ?>
		<!-- notification message -->
		<?php if (isset($_SESSION['success'])) : ?>
     		 <div class="error_success" >
      			<h3>
         		 <?php 
          		echo $_SESSION['success']; 
          		unset($_SESSION['success']);
          		?>
      			</h3>
      		</div>
  		<?php endif ?>
	</div>

</div>
	
<div class="content">
	<form method="post">
		<div class="user_button"> <button type="submit" class="btn" name="rent_car">Rent a Car</button> </div>
		<div class="user_button"> <button type="submit" class="btn" name="view_reservations">View Reservations</button> </div>
		</form>
		</div>
</body>
</html>