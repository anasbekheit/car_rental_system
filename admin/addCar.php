<?php
require_once 'server.php';

if(!isset($_SESSION['admin_user'])){
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_user']);
    header("location: login.php");
}
$plate_id="";
$car_manufacturer="";
$car_model="";
$model_year="";
$car_price="";
$car_country="";
$car_color="";
$car_status="";
$msg="";
$errors=array();



if(isset($_POST['add_car_to_database']))
{
    // receive all input values from the form
    $car_model = mysqli_real_escape_string($db, $_POST['car_model']);
    $car_price = mysqli_real_escape_string($db, $_POST['car_price']);
    $car_manufacturer = mysqli_real_escape_string($db, $_POST['car_manufacturer']);
    $car_color = mysqli_real_escape_string($db, $_POST['car_color']);
    $car_country = mysqli_real_escape_string($db, $_POST['car_country']);
    $model_year = mysqli_real_escape_string($db, $_POST['model_year']);
    $plate_id = mysqli_real_escape_string($db, $_POST['plate_id']);
    $car_status = mysqli_real_escape_string($db, $_POST['car_status']);

    if (empty($plate_id)) { array_push($errors,"Plate id must be entered"); }
    if (empty($car_manufacturer)) { array_push($errors,"Car manufacturer must be entered"); }
    if (empty($car_model)) {array_push($errors,"Car model must be entered");  }
    if (empty($model_year)) {array_push($errors,"Model year must be entered"); }
    if (empty($car_price)) {array_push($errors,"Car price must be entered");}
    if (empty($car_country)) { array_push($errors,"Car country must be entered"); }
    if (empty($car_color)) { array_push($errors,"Car color must be entered"); }
    if (empty($car_status)) { array_push($errors,"Car status must be entered");}
    if (strcmp($car_status, "active")!=0 && strcmp($car_status, "out of service")!=0) { array_push($errors,"Car status is unknown to the database"); }
    if(empty($errors))
    {
        $query="INSERT INTO car (plate_id,car_manufacturer,car_model,model_year,price_per_day,country,color,car_status) 
    VALUES ('$plate_id','$car_manufacturer','$car_model','$model_year','$car_price','$car_country','$car_color','$car_status')";

        $result=mysqli_query($db,$query);
        if($result)
        {
            $msg="Car added successfully";
        }
        else{
            $msg="Car already exists";
        }
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <script type="text/javascript" src="script.js"></script>
    <link rel="stylesheet" type="text/css" href="./css/addcar-styling.css">
</head>
<body>
<div class="header">
    <div class="topbar">
        <a href="index.php"><img class="logo" src="../css/logo/colored/logo-white.svg"></a>
        <!-- logged in user information -->
        <?php  if (isset($_SESSION['admin_user'])) : ?>
            <div class="welcome">
                <h3><strong>Basha</strong></h3>
                <h3 style="color:blue;"> <a class="logoutText" href="index.php?logout='1'" >Log out</a> </h3>
            </div>
        <?php endif ?>
    </div>

</div>

<div class="back"><a href="index.php">< Back</a></div>

<div class="content">

<form name="addCar" action="addCar.php" method="post">
    <div class="textfields">
        <label>Car Plate id</label>
        <input type="text" id="plate_id" name="plate_id">
    </div>
    <div class="textfields">
        <label>Car Manufacturer</label>
        <input type="   text" id="car_manufacturer" name="car_manufacturer">
    </div>
    <div class="textfields">
        <label>Car Model</label>
        <input type="text" id="car_model" name="car_model">
    </div>
    <div class="textfields">
        <label>Model Year</label>
        <input type="text" id="model_year" name="model_year">
    </div>
    <div class="textfields">
        <label>Price per day</label>
        <input type="number" id="car_price" name="car_price">
    </div>

    <div class="textfields">
        <label>Country</label>
        <input type="text" id="car_country" name="car_country">
    </div>

    <div class="textfields">
        <label>Color</label>
        <input type="text" id="car_color" name="car_color">
    </div>
    <div class="textfields">
        <label>Car status</label>
        <input type="text" id="car_status" name="car_status">
    </div>

    <div class="textfields">
        <button type="submit" class="btn" name="add_car_to_database">Add Car</button>
    </div>

    <div class="textfields-error">
        <label><?php include("errors.php");?></label>
    </div>
    <div class="textfields">
        <label><?php echo $msg?></label>
    </div>
</form>

        </div>

        </body>

        </html>

