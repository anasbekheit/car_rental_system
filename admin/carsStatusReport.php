<?php
require_once 'server.php';


if(!isset($_SESSION['admin_user'])){
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_user']);
    header("location: login.php");
}
$on_date="";
$rows=array();
if(isset($_GET['button_search'])) {
    $on_date=mysqli_real_escape_string($db,$_GET['on_date']);
    $query = "SELECT car_status, color, model_year, car_model, car_manufacturer, country, price_per_day, reservation_id, customer_id, reservation_time, total_amount FROM car NATURAL LEFT JOIN reservation WHERE CAST(reservation_time AS DATE)='$on_date'";
    $results = mysqli_query($db, $query);
    if ($results && $results->num_rows > 0) {
        $rows = $results->fetch_all(MYSQLI_ASSOC);
    }
}
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href=".\css\style.css">
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

<form action="carsStatusReport.php" method="get">
    <h>Search Car</h>
    <div class="textfields">
        <label>On</label>
        <input type="date" id="on_date" name="on_date"
               value="<?php echo date("20y-m-d");?>">
    </div>
    <button type="submit" name="button_search" value="button_search"?>>View</button>
</form>
    <div class="results">
        <?php if(!empty($rows)):?>
        <?php foreach ($rows as $row):?>
            <p><?php echo json_encode($row)?></p>
        <?php endforeach;?>
    </div>
<?php else:?>
    <p>    <?php echo "no results"?></p>
<?php endif ?>
</body>

</html>
