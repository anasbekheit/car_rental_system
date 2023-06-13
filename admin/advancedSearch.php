<?php
require_once 'server.php';

if (!isset($_SESSION['admin_user'])) {
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_user']);
    header("location: login.php");
}

$options_array = array();
$table = "";
$column = "";
$value = "";
$rows = array();


if (isset($_POST['advanced_search'])) {
    $table = mysqli_real_escape_string($db, $_POST['table']);
    $column = mysqli_real_escape_string($db, $_POST['column']);
    $value = mysqli_real_escape_string($db, $_POST['search_value']);
    $query = "SELECT * FROM " . $table . " WHERE " . $column . " LIKE \"%" . "$value%\"";

    $results = mysqli_query($db, $query);

    if ($results && $results->num_rows > 0) {
        $rows = $results->fetch_all(MYSQLI_ASSOC);
        if (strcmp($table, "customer") == 0) {
            unset($_SESSION['search_car']);
            unset($_SESSION['search_reservation']);
            $_SESSION['search_customer'] = $rows;
        }
        
        if (strcmp($table, "car") == 0) {
            unset($_SESSION['search_customer']);
            unset($_SESSION['search_reservation']);
            $_SESSION['search_car'] = $rows;
        }
        if (strcmp($table, "reservation") == 0) {
            unset($_SESSION['search_car']);
            unset($_SESSION['search_customer']);
            $_SESSION['search_reservation'] = $rows;
        }

    }
}

?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="./css/advancedsearch-styling.css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        // This is our actual script
        $(document).ready(function () {
            $('#table').change(function () {
                var options = [];
                var optionsValues = [];
                // window.location.href = window.location.href + '?value=' + this.value;
                var table = $("#table option:selected").text();
                if (table.localeCompare("car") == 0) {
                    options = ["plate id", "car model", "car manufacturer", "model year", "price per day", "country", "color", "car status"];
                    optionsValues = ["plate_id", "car_model", "car_manufacturer", "model_year", "price_per_day", "country", "color", "car_status"];

                }
                if (table.localeCompare("customer") == 0) {
                    options = ["customer id", "customer first name", "customer last name", "customer email", "country"];
                    optionsValues = ["customer_id", "fname", "lname", "email", "country"];

                }
                if (table.localeCompare("reservation") == 0) {
                    options = ["reservation id", "car plate id", "customer id", "pickup time", "return time", "reservation time", "total amount"];
                    optionsValues = ["reservation_id", "plate_id", "customer_id", "pickup_time", "return_time", "reservation_time", "total_amount"];

                }
                var min = 0;

                select = document.getElementById('column');
                while (select.lastChild) {
                    select.removeChild(select.lastChild);
                }

                for (var i = min; i < options.length; i++) {
                    var opt = document.createElement('option');
                    opt.value = optionsValues[i];
                    opt.innerHTML = options[i];
                    select.appendChild(opt);
                }

            });
        });

    </script>

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

<div class="back"><a href="index.php">< Back</a></div>

<div class="content">
    <form name="form1" id="form1" action="advancedSearch.php" method="post">
        Search for a: <select name="table" id="table">
            <option value="customer" selected="selected">customer</option>
            <option value="reservation" selected="selected">reservation</option>
            <option value="car" selected="selected">car</option>
        </select>
        <br><br>

        Search by: <select name="column" id="column">
            <?php foreach ($options_array as $option): ?>
                <option value="option" selected="selected"><?php echo $option; ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>
        Value:
        <input type="text" id="value" name="search_value">
        <button type="submit" class="btn" name="advanced_search">Search</button>
        <?php if (!empty($rows)): ?>
            <div class="results">
            <?php if (isset($_SESSION['search_car'])): ?>
                <?php foreach ($rows as $row): ?>
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
                    <br></br>
                <?php endforeach; elseif (isset($_SESSION['search_customer'])): ?>
                <?php foreach ($rows as $row): ?>
                    <h5>Customer id: </h5>
                    <p>
                        <?php
                        echo $row['customer_id']
                        ?></p>
                    <h5>Email: </h5>
                    <p>
                        <?php
                        echo $row['email']
                        ?></p>
                    <h5>First Name: </h5>
                    <p>
                        <?php
                        echo $row['fname']
                        ?></p>
                    <h5>Last Name: </h5>
                    <p>
                        <?php
                        echo $row['lname']
                        ?></p>
                    <h5>Country: </h5>
                    <p>
                        <?php
                        echo $row['country']
                        ?></p>
                    <br></br>
                <?php endforeach;
            elseif (isset($_SESSION['search_reservation'])): ?>
                <?php foreach ($rows as $row): ?>
                    <h5>Reservation ID: </h5>
                    <p>
                        <?php
                        echo $row['reservation_id']
                        ?></p>
                    <h5>Plate ID: </h5>
                    <p>
                        <?php
                        echo $row['plate_id']
                        ?></p>
                    <h5>Custoemr ID: </h5>
                    <p>
                        <?php
                        echo $row['customer_id']
                        ?></p>
                    <h5>Pickup Time: </h5>
                    <p>
                        <?php
                        echo $row['pickup_time']
                        ?></p>
                    <h5>Return Time: </h5>
                    <p>
                        <?php
                        echo $row['return_time']
                        ?></p>
                    <h5>Reservation Time: </h5>
                    <p>
                        <?php
                        echo $row['reservation_time']
                        ?></p><h5>Total Amount: </h5>
                    <p>
                        <?php
                        echo $row['total_amount']
                        ?></p>
                    <br></br>
                <?php endforeach; ?>

                </div>
            <?php endif ?>
        <?php else: ?>
            <p>    <?php echo "no results" ?></p>
        <?php endif ?>
    </form>
</div>

</body>
</html>


