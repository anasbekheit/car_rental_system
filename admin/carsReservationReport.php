<?php
require 'server.php';

if(!isset($_SESSION['admin_user'])){
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_user']);
    header("location: login.php");
}

$column="";
$value="";
$from_date="";
$to_date="";
$rows=array();

if(isset($_GET['car_report']))
{
    $column=mysqli_real_escape_string($db,$_GET['column']);
    $value=mysqli_real_escape_string($db,$_GET['value']);
    $from_date=mysqli_real_escape_string($db,$_GET['from_date']);
    $to_date=mysqli_real_escape_string($db,$_GET['to_date']);
    $query="SELECT * from car NATURAL JOIN reservation WHERE ".$column." = '$value' AND pickup_time BETWEEN '$from_date' AND '$to_date' OR return_time BETWEEN '$from_date' AND '$to_date'";
    $results=mysqli_query($db,$query);
    if($results && $results->num_rows>0){
        $rows=$results->fetch_all(MYSQLI_ASSOC);
    }

}


?>

<html>
<head>

</head>
<body>
<form name="form1" id="form1" action="carsReservationReport.php"  method="get">

    Search by: <select name="column" id="column">
           <option value="plate_id">plate id</option>
           <option value="car_model">car model</option>
        <option value="car_manufacturer">car manufacturer</option>
        <option value="model_year">model year</option>
        <option value="price_per_day">price per day</option>
        <option value="country">country</option>
        <option value="color">color</option>
        <option value="car_status">car status</option>
    </select>
    <br><br>
    Value:
    <input type="text" id="value" name="value">
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
    <button type="submit" class="btn" name="car_report">Search</button>
    <?php if(!empty($rows)):?>
        <div class="results">
            <?php foreach ($rows as $row):?>
                <p><?php echo json_encode($row)?></p>
            <?php endforeach;?>
        </div>
    <?php else:?>
        <p>    <?php echo "no results"?></p>
    <?php endif ?>
</form>

</body>
</html>
