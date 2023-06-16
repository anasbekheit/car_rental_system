<?php
/**
 * @var mysqli $db
 */

require_once 'server.php';

if(!isset($_SESSION['user'])){
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header("location: login.php");
}

$customer_id=$_SESSION['user']['customer_id'];
$query= "SELECT reservation_id,plate_id, pickup_time, return_time, total_amount, color, model_year, car_model, car_manufacturer, country, reservation_time
        FROM reservation NATURAL JOIN car
        WHERE customer_id='$customer_id'";

$rows=array();

$results = mysqli_query($db, $query);
if($results && $results->num_rows>0){
    $rows=$results->fetch_all(MYSQLI_ASSOC);

}


?>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="css/view-reservations-styling.css">
     <title>Your Reservations </title>
    </head>
<body>
    <div class="header">
<div class="topbar">
		<a href="/view/index.html"><img class="logo" src="css/logo/colored/logo-white.svg" alt="Rently's Logo"></a>
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

<div class="back"><a href="view/index.html">< Back</a></div>

<div class="title"><p>Your Reservations</p></div>

<div class="reservations-table">       
  <table class="table">
    <thead class="table-header">
      <tr class="tr">
        <th>Reservation ID</th>
        <th>Plate ID</th>
        <th>Pickup time</th>
        <th>Return time</th>
        <th>Reservation Time</th>
        <th>Total Amount Paid</th>
      </tr>
    </thead>
    <?php   if (!empty($rows) ): ?>

        <?php foreach ($rows as $row) : ?>
    <tbody>

            <td><?php echo $row['reservation_id']?></td>
            <td><?php echo $row['plate_id']?></td>
            <td><?php echo $row['pickup_time']?></td>
            <td><?php echo $row['return_time']?></td>
            <td><?php echo $row['reservation_time']?></td>
            <td><?php echo $row['total_amount']?> LE</td>

    </tbody>
        <?php endforeach ?>

<?php else :?>
<td></td>
<?php  endif ?>
    
  </table>
</div>
</body>
</html>