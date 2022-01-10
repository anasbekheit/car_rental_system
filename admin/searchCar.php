<?php
require 'server.php';

$plate_id="";
$car_manufacturer="";
$car_model="";
$model_year="";
$car_price="";
$car_country="";
$car_color="";
$car_status="";
$errors=array();

if(!isset($_SESSION['admin_user'])){
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_user']);
    header("location: login.php");
}


if(isset($_POST['search_car']))
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

    $query="SELECT plate_id,car_model,car_manufacturer,color,model_year,country,price_per_day,car_status FROM car WHERE 1 ";
    $where="";
    if (!empty($car_model)) { $where.="AND car_model = '$car_model' "; }
    if (!empty($car_price)) { $where.="AND price_per_day <= '$car_price' "; }
    if (!empty($car_manufacturer)) { $where.="AND car_manufacturer ='$car_manufacturer' "; }
    if (!empty($car_country)) { $where.="AND country='$car_country' "; }
    if (!empty($car_color)) { $where.="AND color= '$car_color' "; }
    if (!empty($car_status)) { $where.="AND car_status= '$car_status' "; }
    if (!empty($plate_id)) { $where.="AND plate_id= '$plate_id' "; }
    if (!empty($model_year)) { $where.="AND model_year= '$model_year' "; }
    $query.=$where;
    echo  $query;
    $results = mysqli_query($db, $query);

    if($results && $results->num_rows>0){
        $rows=$results->fetch_all(MYSQLI_ASSOC);
    }

}

if(isset($_POST['button_edit'])){
    $plate_id=$_POST['button_edit'];
    $query_edit="SELECT * from car where  plate_id = '$plate_id'";
    $result_edit = mysqli_query($db, $query_edit);
    $edited_car = mysqli_fetch_assoc($result_edit);
    $_SESSION['edited_car']=$edited_car;
    header("location: editCar.php");
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <script type="text/javascript" src="script.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/search-styling.css">
</head>
<body>
<div class="header">
    <div class="topbar">
        <a href="index.php"><img class="logo" src="../css/logo/colored/logo-white.svg"></a>
        <!-- logged in user information -->
        <?php if (isset($_SESSION['user'])) : ?>
            <div class="welcome">
                <h3><strong><?php echo $_SESSION['user']['fname']; ?></strong></h3>
                <h3><a class="logoutText" href="index.php?logout='1'">Log out</a></h3>
            </div>
        <?php endif ?>
        <!-- notification message -->
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="error_success">
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
<form name="addCar" action="searchCar.php" method="post">
    <div class="textfields">
        <label>Car Plate id</label>
        <input type="text" id="plate_id" name="plate_id">
    </div>
    <div class="textfields">
        <label>Car Manufacturer</label>
        <input type="text" id="car_manufacturer" name="car_manufacturer">
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
        <button type="submit" class="btn" name="search_car">Search</button>
    </div>
</form>

<?php
if (!empty($rows)) : ?>

    <div  class="main">
        <form action="searchCar.php" method="post">

            <?php foreach ($rows as $row) : ?>
                <h5>Car Plate id: </h5>
                <p>
                    <?php
                    echo $row['plate_id']
                    ?></p>
                <h5>Car Model: </h5>
                <p>
                    <?php
                    echo $row['car_model']
                    ?></p>
                <h5>Car Manufacturer: </h5>
                <p>
                    <?php
                    echo $row['car_manufacturer']
                    ?></p>
                <h5>Car Model Year: </h5>
                <p>
                    <?php
                    echo $row['model_year']
                    ?></p>
                <h5>Car Price Per Day: </h5>
                <p>
                    <?php
                    echo $row['price_per_day']
                    ?></p>
                <h5>Color: </h5>
                <p>
                    <?php
                    echo $row['color']
                    ?></p>
                <h5>Car Country: </h5>
                <p>
                    <?php
                    echo $row['country']
                    ?></p>
                <h5>Car Status: </h5>
                <p>
                    <?php
                    echo $row['car_status']
                    ?></p>
                <button type="submit" name="button_edit" value=<?php echo $row['plate_id'];?>>Edit</button>
                <br></br>
            <?php endforeach ?>
        </form>
    </div>

<?php else :?>
    <p>No results</p>
<?php  endif ?>

</body>
</html>
