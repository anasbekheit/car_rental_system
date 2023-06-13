<?php
//TODO: FIX NAMING CONVENTIONS HTML
//TODO: STYLING IN CSS
require_once 'server.php';

if(!isset($_SESSION['admin_user'])){
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_user']);
    header("location: login.php");
}
if(isset($_POST['report1']))
{
    header("location: reservationsReport.php");
}
if(isset($_POST['report2']))
{
    header("location: carsReservationReport.php");
}
if(isset($_POST['report3']))
{
    header("location: carsStatusReport.php");
}
if(isset($_POST['report4']))
{
    header("location: customersReport.php");
}
if(isset($_POST['report5']))
{
    header("location: viewReservationsMade.php");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href=".\css\reports-styling.css">
</head>
<body>
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
    <div class="title">
        <h2>Reports</h2>
    </div>
    <form method="post">
        <div class="user_button"> <button type="submit" class="btn" name="report1">View Reservations Report</button> </div>
        <div class="user_button"> <button type="submit" class="btn" name="report2">View Cars Report</button> </div>
        <div class="user_button"> <button type="submit" class="btn" name="report3">View Cars Status Report</button> </div>
        <div class="user_button"> <button type="submit" class="btn" name="report4">View Customers Report</button> </div>
        <div class="user_button"> <button type="submit" class="btn" name="report5">View Reservations Made Today</button> </div>

    </form>
</div>
</body>
</html>
