<?php
require_once 'server.php';

if(!isset($_SESSION['admin_user'])){
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_user']);
    header("location: login.php");
}

$start_date="";
$end_date="";
if(isset($_GET['reservation_search']))
{

$start_date=mysqli_real_escape_string($db,$_GET['start_date']);
$end_date=mysqli_real_escape_string($db,$_GET['end_date']);
$query="SELECT *,sum(total_amount) as total from (reservation as R JOIN customer as C ON R.customer_id = C.customer_id)  JOIN car as C2 ON R.plate_id = C2.plate_id WHERE CAST(reservation_time AS DATE)  BETWEEN '$start_date' AND '$end_date'";
echo $query;
$results=mysqli_query($db,$query);
if($results && $results->num_rows>0){
$rows=$results->fetch_all(MYSQLI_ASSOC);
}

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href=".\css\style.css">
    <title>Reservation Report</title>
</head>
<body>


<div class="header">
    <div class="topbar">
        <a href="index.php"><img class="logo" src="../css/logo/colored/logo-white.svg"></a>
        <!-- logged in user information -->
        <?php if (isset($_SESSION['admin_user'])) : ?>
            <div class="welcome">
                <h3><strong>Basha</strong></h3>
                <h3 style="color:blue;"><a class="logoutText" href="index.php?logout='1'">Log out</a></h3>
            </div>
        <?php endif ?>
    </div>

</div>
<form name="reservationReport" action="viewReservationsMade.php" method="get">

    <div class="textfields">
        <label>From</label>
        <input type="date" id="start_date" name="start_date"
               value="<?php echo date("20y-m-d");?>">
    </div>
    <div class="textfields">
        <label>To</label>
        <input type="date" id="end_date" name="end_date"
               value="<?php echo Date('20y-m-d', strtotime('+1 days'))?>">
    </div>
    <div class="textfields">
        <button type="submit" class="btn" name="reservation_search">Search</button>
    </div>
    <div>
        <?php if(!empty($rows)):?>
            <div class="results">
                <?php foreach ($rows as $row):?>
                    <p><?php echo json_encode($row)?></p>
                <?php endforeach;?>
            </div>
        <?php else:?>
            <p>    <?php echo "no reservations"?></p>
        <?php endif ?>
    </div>
</form>
</body>
</html>
