<?php
require_once 'server.php';

$results = array();


if (!isset($_SESSION['user'])) {
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header("location: login.php");
}
if (!isset($_SESSION['car'])) {
    header('location: search.php');
}
if (isset($_SESSION['reservation']))
{unset($_SESSION['reservation']);}

if (isset($_POST['user_proceed_to_checkout'])) {

    $_SESSION['reservation'] = "Reservation in progress";
    header('location: checkout.php');
}
if (isset($_POST['back_to_search'])) {

    unset($_SESSION['car']);
    if (isset($_SESSION['reservation']))
    {unset($_SESSION['reservation']);}
    header('location: search.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/reservation-styling.css">
    <title>Reservation</title>
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

<div class="back"><a href="index.php">< Back</a></div>
    
<div class="car-details">
<form method="post">
    <p><strong>Plate ID:</strong> <?php echo$_SESSION['car']['plate_id']; ?></p>
    <p><strong>Car Manufaturer:</strong> <?php echo$_SESSION['car']['car_manufacturer']; ?></p>
    <p><strong>Car Model:</strong> <?php echo$_SESSION['car']['car_model']; ?></p>
    <p><strong>Model Year:</strong> <?php echo$_SESSION['car']['model_year']; ?></p>
    <p><strong>Car Color:</strong> <?php echo$_SESSION['car']['color']; ?></p>
    <p><strong>Car Country:</strong> <?php echo$_SESSION['car']['country']; ?></p>
    <p><strong>Price Per Day:</strong> <?php echo$_SESSION['car']['price_per_day']; ?></p>
    <p><strong>Car Status:</strong> <?php echo$_SESSION['car']['car_status']; ?></p>
    <p><strong>Available From:</strong> <?php echo $_SESSION['from_date']; ?></p>
    <p><strong>To:</strong> <?php echo $_SESSION['to_date']; ?></p>
    <p><strong>Total Amount:</strong> LE <?php
        $from_date = date_create($_SESSION['from_date']);
        $to_date = date_create($_SESSION['to_date']);
        $days = date_diff($from_date, $to_date);
        $day = $days->d;
        $total_amount = $day * $_SESSION['car']['price_per_day'];
        $_SESSION['total_amount'] = $total_amount;
        echo $total_amount;
        ?></p>
    <div class="buttons">
    <button type="submit" name="back_to_search" value="back_to_search"> << Back</button>
    <button type="submit" name="user_proceed_to_checkout" value="confirm">Proceed to Checkout</button>
        </div>

</form>
        </div>
</body>
</html>