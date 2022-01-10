<?php
require 'server.php';

if (!isset($_SESSION['user'])) {
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header("location: login.php");
}

//initializing variables
$car_model = "";
$car_manufacturer = "";
$car_color = "";
$car_country = "";
$car_price = "";
$plate_id = "";
$from_date = "";
$to_date = "";
$rows = array();


if (isset($_GET['user_search'])) {
    // receive all input values from the form
    $car_model = mysqli_real_escape_string($db, $_GET['car_model']);
    $car_price = mysqli_real_escape_string($db, $_GET['car_price']);
    $car_manufacturer = mysqli_real_escape_string($db, $_GET['car_manufacturer']);
    $car_color = mysqli_real_escape_string($db, $_GET['car_color']);
    $car_country = mysqli_real_escape_string($db, $_GET['car_country']);
    $from_date = mysqli_real_escape_string($db, $_GET['from_date']);
    $to_date = mysqli_real_escape_string($db, $_GET['to_date']);

    $query = "SELECT plate_id,car_model,car_manufacturer,color,model_year,country,price_per_day,car_status FROM car  WHERE car_status = 'active' ";
    $where = "";
    if (!empty($car_model)) {
        $where .= "AND car_model = '$car_model' ";
    }
    if (!empty($car_price)) {
        $where .= "AND price_per_day <= '$car_price' ";
    }
    if (!empty($car_manufacturer)) {
        $where .= "AND car_manufacturer ='$car_manufacturer' ";
    }
    if (!empty($car_country)) {
        $where .= "AND country='$car_country' ";
    }
    if (!empty($car_color)) {
        $where .= "AND color= '$car_color' ";
    }
    $query .= $where;
    $query .= "EXCEPT (SELECT plate_id,car_model,car_manufacturer,color,model_year,country,price_per_day,car_status
    FROM reservation NATURAL JOIN car WHERE '$from_date' BETWEEN pickup_time AND return_time OR '$to_date' BETWEEN pickup_time AND return_time)";
    $results = mysqli_query($db, $query);


    if ($results && $results->num_rows > 0) {
        $rows = $results->fetch_all(MYSQLI_ASSOC);
        $_SESSION['result'] = "success";
        $_SESSION['from_date'] = $from_date;
        $_SESSION['to_date'] = $to_date;
    } else {
        unset($_SESSION['result']);
    }

}


if (isset($_POST['button'])) {
    $plate_id = $_POST['button'];
    $query = "SELECT * FROM car Where plate_id ='$plate_id'";
    echo $query;
    $result_reserve = mysqli_query($db, $query);
    $car = mysqli_fetch_assoc($result_reserve);
    $_SESSION['car'] = $car;
    header("location: reservation.php");

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <script type="text/javascript" src="script.js"></script>
    <link rel="stylesheet" type="text/css" href="./css/search-styling.css">
</head>
<body>
<div class="header">
    <div class="topbar">
        <a href="index.php"><img class="logo" src="css/logo/colored/logo-white.svg"></a>
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

<div class="search">
    <div class="searchForm">
        <form class="searchForm" name="searchForm" action="search.php" method="get">
            <div class="upper-paramters">
              <div class="textfields">
                  <label>Car Manufacturer</label>
                  <input type="text" id="car_manufacturer" name="car_manufacturer">
              </div>
              <div class="textfields">
                  <label>Car Model</label>
                  <input type="text" id="car_model" name="car_model">
              </div>
              <div class="textfields">
                  <label>Price per day</label>
                  <input type="number" id="car_price" name="car_price">
              </div>

              <div class="textfields">
                  <label>Country</label>
                  <input type="text" id="car_country" name="car_country">
              </div>
        </div>

            <div class="lower-parameters">
              <div class="textfields">
                  <label>Color</label>
                  <input type="text" id="car_color" name="car_color">
              </div>
              <div class="textfields">
                  <label>From</label>
                  <input type="date" id="from_date" name="from_date"
                        value="<?php echo date("20y-m-d"); ?>"
                        min="<?php echo date("20y-m-d"); ?>" max="<?php echo Date('20y-m-d', strtotime('+1 month')) ?>">
              </div>
              <div class="textfields">
                  <label>To</label>
                  <input type="date" id="to_date" name="to_date"
                        value="<?php echo Date('20y-m-d', strtotime('+1 days')) ?>"
                        min="<?php echo Date('20y-m-d', strtotime('+1 days')) ?>"
                        max="<?php echo Date('20y-m-d', strtotime('+2 month')) ?>">
              </div>
              <div class="textfields-search">
                  <button type="submit" class="btn" name="user_search">Search</button>
              </div>
            </div>

        </form>
    </div>

    <div class="results">
        <?php
        if (!empty($rows)) : ?>

        <div class="main">
            <form action="search.php" method="post">
                <?php foreach ($rows as $row) : ?>
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
                    <button type="submit" name="button" value=<?php echo $row['plate_id']; ?>>View</button>
                    <br></br>
                <?php endforeach ?>
            </form>

            <?php else : ?>
                <p>No results</p>
            <?php endif ?>

        </div>
    </div>
</div>


</body>
</html>

