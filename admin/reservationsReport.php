<?php
require_once 'server.php';

if (!isset($_SESSION['admin_user'])) {
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_user']);
    header("location: login.php");
}

$from_date = "";
$to_date = "";
if (isset($_GET['reservation_search'])) {
    $from_date = mysqli_real_escape_string($db, $_GET['from_date']);
    $to_date = mysqli_real_escape_string($db, $_GET['to_date']);
    $query = "SELECT *,sum(total_amount) as total from (reservation as R JOIN customer as C ON R.customer_id = C.customer_id)  JOIN car as C2 ON R.plate_id = C2.plate_id WHERE pickup_time BETWEEN '$from_date' AND '$to_date' OR return_time BETWEEN '$from_date' AND '$to_date'";

    $results = mysqli_query($db, $query);
    if ($results && $results->num_rows > 0) {
        $rows = $results->fetch_all(MYSQLI_ASSOC);
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href=".\css\reservations-report-styling.css">
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

<div class="back"><a href="index.php">< Back</a></div>

<div class="content">
    <form name="reservationReport" action="reservationsReport.php" method="get">
        <div class="search">
            <div class="textfields">
                <label>From</label>
                <input type="date" id="from_date" name="from_date"
                       value="<?php echo date("20y-m-d"); ?>">
            </div>
            <div class="textfields">
                <label>To</label>
                <input type="date" id="to_date" name="to_date"
                       value="<?php echo Date('20y-m-d', strtotime('+1 days')) ?>">
            </div>
            <div class="textfields">
                <button type="submit" class="btn" name="reservation_search">Search</button>
            </div>
            <div>
            </div>

            <div class="results">
                <?php if (!empty($rows)): ?>
                    <table class="table">
                    <thead class="table-header">
                    <tr class="tr">
                        <th>Plate ID</th>    
                        <th>Reservation ID</th>
                        <th>Customer ID</th>
                        <th>Pickup time</th>
                        <th>Return time</th>
                        <th>Reservation Time</th>
                        <th>Total Amount Paid</th>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Country</th>
                        <th>Car Status</th>
                        <th>Color</th>
                        <th>Model Year</th>
                        <th>Car Model</th>
                        <th>Car Manufacturer</th>
                        <th>Price Per Day</th>
                    </tr>
                    </thead>
                    <?php if (!empty($rows)): ?>

                        <?php foreach ($rows as $row) : ?>
                            <tbody>
                            <td><?php echo $row['plate_id'] ?></td>
                            <td><?php echo $row['reservation_id'] ?></td>
                            <td><?php echo $row['customer_id'] ?></td>
                            <td><?php echo $row['pickup_time'] ?></td>
                            <td><?php echo $row['return_time'] ?></td>
                            <td><?php echo $row['reservation_time'] ?></td>
                            <td><?php echo $row['total_amount'] ?> LE</td>
                            <td><?php echo $row['email'] ?> LE</td>
                            <td><?php echo $row['fname'] ?></td>
                            <td><?php echo $row['lname'] ?></td>
                            <td><?php echo $row['country'] ?></td>
                            <td><?php echo $row['car_status'] ?></td>
                            <td><?php echo $row['color'] ?></td>
                            <td><?php echo $row['model_year'] ?></td>
                            <td><?php echo $row['car_model'] ?></td>
                            <td><?php echo $row['car_manufacturer'] ?></td>
                            <td><?php echo $row['price_per_day'] ?></td>
                            </tbody>
                        <?php endforeach ?>

                    <?php else : ?>
                        <td></td>
                    <?php endif ?>

                </table>
                <?php else: ?>
                    <p>    <?php echo "no reservations" ?></p>
                <?php endif ?>
            </div>
    </form>
</div>
</body>
</html>
