<?php require_once 'server.php';

if (!isset($_SESSION['user'])) {
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header("location: login.php");
}

if(!isset($_SESSION['reservation']))
{
    header('location: reservation.php');
}
if(!isset($_SESSION['car']))
{
    header('location: search.php');
}

if(isset($_POST['pay_button'])){
    $plate_id=$_SESSION['car']['plate_id'];
    $customer_id=$_SESSION['user']['customer_id'];
    $pickup_time=$_SESSION['from_date'];
    $return_date=$_SESSION['to_date'];

    /*    $query = "
    INSERT INTO reservation (plate_id, customer_id, pickup_time, return_time) 
    SELECT '$plate_id','$customer_id','$pickup_time','$return_date'
    FROM dual
    WHERE NOT EXISTS (SELECT plate_id,customer_id,pickup_time,return_time FROM reservation
                        WHERE ( ('$pickup_time' BETWEEN pickup_time AND return_time) 
                            OR ('$return_date' BETWEEN pickup_time AND return_time) )  AND plate_id='$plate_id'); ";*/

    $query = "
    INSERT INTO reservation (plate_id, customer_id, pickup_time, return_time) 
    VALUES ('$plate_id','$customer_id','$pickup_time','$return_date')";
    $result = mysqli_query($db, $query);

     if($result)
     {
         echo "reservation made"."<br>";

         header('location: viewReservation.php');
     }
     else
     {
         echo "car is already reserved"."<br>";
        // header('location: viwReservation.php');
     }

}

?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="css\checkout-styling.css">
    <title>Check Out</title>
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

<div class="content">
    <p><strong>Customer Information</strong></p>
    <p><strong>Name: </strong><?php echo$_SESSION['user']['fname']?> <?php echo$_SESSION['user']['lname']?></p>
    <p><strong>Credit Card Number: </strong><?php echo str_replace(range(0,9), "*", substr($_SESSION['user']['credit_card'], 0, -4)) .  substr($_SESSION['user']['credit_card'], -4)?></p>

    <br></br>

    <p><strong>Total Amount: </strong><?php echo$_SESSION['total_amount']?></p>
    <form name="payment" method="post">
        <button type="submit" name="pay_button">PAY</button>
    </form>
</div>

</body>

</html>
