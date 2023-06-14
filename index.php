<?php
require_once 'server.php';

if (!isset($_SESSION['user'])) {
    header('location: login.html');
    exit;
}
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header("location: login.html");
    exit;
}
if (isset($_POST['rent_car'])) {
    header("location: search.html");
    exit;
}
if (isset($_POST['view_reservations'])) {
    header("location: viewReservation.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="css/index-styling.css">
    <script src="index.js"></script>
</head>
<body>

<div class="header">
    <div class="topbar">
        <a href="index.php"><img class="logo" src="css/logo/colored/logo-white.svg" alt="Rently Logo"></a>
        <!-- logged in user information -->
        <div class="welcome">
            <h3><strong id="username"> <?php echo $_SESSION['user']['fname']; ?></php></strong></h3>
            <h3><a class="logoutText" href="#" id="logoutLink">Log out</a></h3>
        </div>
        <!-- notification message -->
        <div class="error_success">
            <h3 id="successMessage"></h3>
        </div>
    </div>
</div>

<div class="content">
    <form>
        <div class="user_button">
            <button type="button" class="btn" id="rentCarButton">Rent a Car</button>
        </div>
        <div class="user_button">
            <button type="button" class="btn" id="viewReservationsButton">View Reservations</button>
        </div>
    </form>
</div>
</body>
</html>