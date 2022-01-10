<?php
require 'server.php';

if(!isset($_SESSION['admin_user'])){
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_user']);
    header("location: login.php");
}

$from_date="";
$to_date="";
if(isset($_GET['reservation_search']))
{
$from_date=mysqli_real_escape_string($db,$_GET['from_date']);
$to_date=mysqli_real_escape_string($db,$_GET['to_date']);
$query="SELECT *,sum(total_amount) as total from (reservation as R JOIN customer as C ON R.customer_id = C.customer_id)  JOIN car as C2 ON R.plate_id = C2.plate_id WHERE pickup_time BETWEEN '$from_date' AND '$to_date' OR return_time BETWEEN '$from_date' AND '$to_date'";
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
    <title>Reservation Report</title>
</head>
<body>
<form name="reservationReport" action="reservationsReport.php" method="get">

    <div class="textfields">
        <label>From</label>
        <input type="date" id="from_date" name="from_date"
               value="<?php echo date("20y-m-d");?>">
    </div>
    <div class="textfields">
        <label>To</label>
        <input type="date" id="to_date" name="to_date"
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
